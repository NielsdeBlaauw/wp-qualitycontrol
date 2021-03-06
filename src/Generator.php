<?php

namespace NDB\QualityControl;

class Generator{
  const META_IDENTIFIER_KEY = 'wp-qc-generated';
  const NB_POSTS_PER_TYPE = 10;

  public $post_types = array();

  public function __construct(){
    $this->faker = \Faker\Factory::create();
    $this->faker->addProvider(new \Faker\Provider\Internet($this->faker));
    $this->faker->addProvider(new \Faker\Provider\DateTime($this->faker));
    $this->faker->addProvider(new \Faker\Provider\en_US\Address($this->faker));
    $this->faker->addProvider(new \Faker\Provider\Miscellaneous($this->faker));
    $this->faker->addProvider(new \Faker\Provider\Internet($this->faker));
    $this->faker->addProvider(new \Faker\Provider\HtmlLorem($this->faker));
    $this->faker->addProvider(new \Faker\Provider\en_US\Person($this->faker));
    $this->faker->addProvider(new \Faker\Provider\en_US\Text($this->faker));
    $pre_config = new \Noodlehaus\Config(array('?' . realpath(__DIR__ . '/../../../../qualitycontrol.dist.json')));
    $config_files = array_merge(
      array('?' . realpath(__DIR__ . '/../../../../qualitycontrol.dist.json')), 
      $this->parse_config_files($pre_config->get('settings.files', array())),
      array('?' . realpath(__DIR__ . '/../../../../qualitycontrol.json'))
    );

    $this->config = new \Noodlehaus\Config($config_files);
    $this->map_post_types();
    $this->map_user_roles();
    $this->map_taxonomies();
  }

  protected function parse_config_files($paths){
    $real_paths = array();
    foreach($paths as $path){
      $real_path = realpath(__DIR__ . '/../../../../' . $path);
      if(empty($real_path)){
        \WP_CLI::warning('Config file not found: ' . $path);
      }else{
        $real_paths[] = $real_path;
      }
    }
    return $real_paths;
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

  protected function map_user_roles(){
    $wp_roles = wp_roles();
    foreach($wp_roles->role_objects as $role){
      $this->user_roles[] = new UserRole($role, $this);
    }
  }

  protected function map_taxonomies(){
    $taxonomies = get_taxonomies(array(), 'objects');
    foreach($taxonomies as $taxonomy){
      $this->taxonomies[] = new Taxonomy($taxonomy, $this);
    }
  }

  public function generate(){
    $options_page = new \NDB\QualityControl\OptionsPage($this);
    $options_page->generate();
    $this->generators = array_merge($this->user_roles, $this->taxonomies, $this->post_types, array($options_page));
    $nb_posts_total = array_reduce($this->generators, function($val, $item){
      return $val += $item->nb_posts;
    },  0);
    usort($this->generators, function($a, $b){
      if ($a->process_order == $b->process_order) {
        return 0;
      }
      return ($a->process_order < $b->process_order) ? -1 : 1;
    });
    foreach($this->generators as $generator){
      $progress = \WP_CLI\Utils\make_progress_bar( "Creating items for {$generator->get_name()}", $generator->nb_posts );
      for($i = 0; $i < $generator->nb_posts; $i += 1){
        $generator->generate();
        $progress->tick();
      }
      $progress->finish();
    }
  }
}