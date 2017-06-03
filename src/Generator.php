<?php

namespace NDB\ACFQC;

class Generator{
  const META_IDENTIFIER_KEY = 'acf-qc-generated';
  const NB_POSTS_PER_TYPE = 2;

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
    $post_types = get_post_types(array(
      'public'=>true,
      '_builtin'=>true,
    ), 'objects');
    foreach($post_types as $post_type){
      if(!in_array($post_type->name, $skippable_types)){
        $this->post_types[] = new PostType($post_type, $this);
      }
    }
  }

  public function generate(){
    foreach($this->post_types as $post_type){
      \WP_CLI::log(sprintf('Creating post for post_type %s', $post_type->post_type->name));
      for($i = 0; $i < self::NB_POSTS_PER_TYPE; $i += 1){
        \WP_CLI::line(sprintf('Generating %s %d/%d', $post_type->post_type->name, $i+1, self::NB_POSTS_PER_TYPE));
        $post_type->generate();
      }
    }
  }
}