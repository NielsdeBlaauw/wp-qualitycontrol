<?php

namespace NDB\QualityControl;
use \GuzzleHttp\Pool;
use \GuzzleHttp\Client;
use \GuzzleHttp\Psr7\Request;
/**
 * Makes the wp-qc wp-cli command available
 */
class Command extends \WP_CLI_Command{
  public static $warnings = array();

  /**
   * Create fuzzy posts and test them.
   *
   * ## EXAMPLES
   *
   *     wp qualitycontrol generate
   */
  public function generate(array $args, array $args_assoc) : bool{
    $this->optimize();
    \WP_CLI::line('Starting generation of WordPress objects.');
    $generator = new Generator();
    $generator->generate();
    $this->finish_optimize();
    if(!empty(self::$warnings)){
      \WP_CLI::error_multi_line(self::$warnings);
    }
    
    return false;
  }

  protected function optimize(){
    global $wpdb;
    $wpdb->query('START TRANSACTION');
    wp_defer_term_counting(true);
  }
  protected function finish_optimize(){
    global $wpdb;
    $wpdb->query('COMMIT');
    wp_defer_term_counting(false);
  }

  protected function response_status_code_test($failed, $response) : bool{
    if($response->getStatusCode() !== 200){
      self::$warnings[$request->getURI() . '_status_code'] = sprintf("Produced code %s from url: %s",
        $response->getStatusCode(),
        $response->getHeaders()['Link'][1]
      );
      return true;
    }
    return $failed;
  }

  public function test(): bool{
    $posts = get_posts(array(
      'post_type'=>'any',
      'post_status'=>'any',
      'meta_key'=>Generator::META_IDENTIFIER_KEY,
      'meta_value'=>'1',
      'fields'=>'ids',
      'posts_per_page'=>-1
    ));    
    $failed = false;
    $progress = \WP_CLI\Utils\make_progress_bar( 'Testing response codes of generated posts', count($posts) );
    $requests = array();

    $client = new \GuzzleHttp\Client();

    $requests = function ($posts) {
      foreach($posts as $post){
        yield new \GuzzleHttp\Psr7\Request('GET', get_post_permalink( $post ));
      }
    };

    $pool = new \GuzzleHttp\Pool($client, $requests($posts), [
      'concurrency' => 5,
      'fulfilled' => function ($response, $index) use ($progress, &$failed){
          $instanceFailed = apply_filters('ndb/qualitycontrol/test/fulfilled', $this->response_status_code_test(false, $response), $response);
          if($instanceFailed){
            $failed = true;
          }
          $progress->tick();
      },
      'rejected' => function ($reason, $index) use ($progress, &$failed){
        self::$warnings['test_url_' . $index . '_rejected'] = sprintf("Produced code %s from url: %s",
          $reason->getResponse()->getStatusCode(),
          $reason->getRequest()->getURI()
        );
        $instanceFailed = apply_filters('ndb/qualitycontrol/test/rejected', true, $reason);
        if($instanceFailed){
          $failed = true;
        }
        $progress->tick();
      },
    ]);
    $promise = $pool->promise();
    $promise->wait();
    if($failed){
      \WP_CLI::warning("Some posts failed to produce a 200 code. Generated posts will not be deleted.");
    }else{
      $progress->finish();
    }
    return $failed;
  }

  public function clean(){
    PostType::clean();
    UserRole::clean();
    Taxonomy::clean();
    OptionsPage::clean();
  }
}
