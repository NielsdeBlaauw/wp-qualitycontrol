<?php

namespace NDB\QualityControl;

class Generator{
  const META_IDENTIFIER_KEY = 'wp-qc-generated';
  const NB_POSTS_PER_TYPE = 10;

  public $post_types = array();
  public $options = array();

  public function __construct(array $options){
    $this->faker = \Faker\Factory::create();
    $this->faker->addProvider(new \Faker\Provider\Internet($this->faker));
    $this->faker->addProvider(new \Faker\Provider\DateTime($this->faker));
    $this->faker->addProvider(new \Faker\Provider\Miscellaneous($this->faker));
    $this->faker->addProvider(new \Faker\Provider\Internet($this->faker));
    $this->faker->addProvider(new \Faker\Provider\en_US\Text($this->faker));
    $this->options = $options;
    $this->map_post_types();
  }

  protected function map_post_types(){
    $skippable_types = array('attachment');
    $built_in_post_types = get_post_types(array(
      'public'=>true,
      '_builtin'=>true,
    ), 'objects');
    $custom_post_types = get_post_types(array(
      'public'=>true,
      '_builtin'=>false,
    ), 'objects');
    $post_types = array_merge($built_in_post_types, $custom_post_types);
    foreach($post_types as $post_type){
      if(!in_array($post_type->name, $skippable_types)){
        $this->post_types[] = new PostType($post_type, $this);
      }
    }
  }

  public function generate(){
    $progress = \WP_CLI\Utils\make_progress_bar( 'Creating fuzzy posts', count($this->post_types) * self::NB_POSTS_PER_TYPE );
    foreach($this->post_types as $post_type){
      for($i = 0; $i < self::NB_POSTS_PER_TYPE; $i += 1){
        $post_type->generate();
        $progress->tick();
      }
    }
    $progress->finish();
  }
}