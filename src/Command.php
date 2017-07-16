<?php

namespace NDB\QualityControl;

/**
 * Makes the wp-qc wp-cli command available
 */
class Command extends \WP_CLI_Command{
  public static $warnings = array();

  /**
   * Create fuzzy posts and test them.
   *
   * [--number-of-posts=<integer>]
   * : Number of posts to generate per post_type
   *
   * [--skip-clean-after-run]
   * : Deletes the created fuzzy posts after running
   *
   * [--skip-tests]
   * : Skip response code tests on created posts
   *
   *
   * ## EXAMPLES
   *
   *     wp qualitycontrol generate --prompt
   */
  public function generate(array $args, array $args_assoc) : bool{
    $options = wp_parse_args($args_assoc, array(
      'number-of-posts'=>5,
      'skip-clean-after-run'=>false,
      'skip-tests'=>false
    ));
    \WP_CLI::line('Starting generation of WordPress objects.');
    $this->clean();
    $generator = new Generator($options);
    $generator->generate();
    if(!$options['skip-tests']){
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

  public function test(): bool{
    $posts = $this->get_all_generated_posts();
    $failed = array();
    $progress = \WP_CLI\Utils\make_progress_bar( 'Testing response codes of generated posts', count($posts) );
    foreach($posts as $post){
      $response = wp_remote_get( get_post_permalink( $post ) );
      if(wp_remote_retrieve_response_code($response) !== 200){
        $failed[] = $post;
        self::$warnings[] = sprintf("Post %s produced code %s from url: %s",
          $post,
          wp_remote_retrieve_response_code($response),
          get_post_permalink( $post )
        );
      }
      $progress->tick();
    }
    if(!empty($failed)){
      \WP_CLI::warning("Some posts failed to produce a 200 code. Generated posts will not be deleted.");
    }else{
      $progress->finish();
    }
    return !empty($failed);
  }

  public function clean(){
    $posts = $this->get_all_generated_posts();
    $progress = \WP_CLI\Utils\make_progress_bar( 'Cleaning generated posts', count($posts) );
    foreach($posts as $post_id){
      wp_delete_post($post_id, true);
      $progress->tick();
    }
    $progress->finish();
  }

  protected function get_all_generated_posts(){
    $posts = get_posts(array(
      'post_type'=>'any',
      'post_status'=>'any',
      'meta_key'=>Generator::META_IDENTIFIER_KEY,
      'meta_value'=>'1',
      'fields'=>'ids',
      'posts_per_page'=>-1
    ));
    return $posts;
  }
}