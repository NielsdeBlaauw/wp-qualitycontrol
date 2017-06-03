<?php

namespace NDB\ACFQC;

/**
 * Makes the acf-qc wp-cli command available
 */
class Command extends \WP_CLI_Command{
  public function generate(array $args, array $args_assoc) : bool{
    \WP_CLI::line('Starting generation of WordPress objects.');
    $generator = new Generator(array());
    $generator->generate();
    return true;
  }

  public function clean(){
    $posts = get_posts(array(
      'post_type'=>'any',
      'post_status'=>'any',
      'meta_key'=>Generator::META_IDENTIFIER_KEY,
      'meta_value'=>'1',
      'fields'=>'ids',
      'posts_per_page'=>-1
    ));
    foreach($posts as $post_id){
      \WP_CLI::log(sprintf('Removing post %d', $post_id));
      wp_delete_post($post_id, true);
    }
  }
}