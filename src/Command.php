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
   * [--skip-clean-after-run]
   * : Deletes the created fuzzy posts after running
   *
   * [--skip-tests]
   * : Skip response code tests on created posts
   *
   * [--concurrent-requests=<integer>]
   * : Number of concurrent test requests
   *
   *
   * ## EXAMPLES
   *
   *     wp qualitycontrol generate --prompt
   */
  public function generate(array $args, array $args_assoc) : bool{
    $this->optimize();
    $options = wp_parse_args($args_assoc, array(
      'skip-clean-after-run'=>false,
      'skip-tests'=>false,
      'concurrent-requests'=>5,
    ));
    $this->options = $options;
    \WP_CLI::line('Starting generation of WordPress objects.');
    $this->clean();
    $generator = new Generator();
    $generator->generate();
    $this->finish_optimize();
    if(!$options['skip-tests']){
      $this->set_up_test();
      $testresult = $this->test();
    }
    if(!$options['skip-clean-after-run'] && !$testresult){
      $this->clean();
    }
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

  protected function set_up_test(){
    add_filter('ndb/qualitycontrol/test/fulfilled', array($this, 'response_status_code_test'), 10, 2);
  }

  public function response_status_code_test($failed, $response) : bool{
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
    $posts = $this->get_all_generated_posts();
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
      'concurrency' => $this->options['concurrent-requests'],
      'fulfilled' => function ($response, $index) use ($progress, &$failed){
          $instanceFailed = apply_filters('ndb/qualitycontrol/test/fulfilled', false, $response);
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
