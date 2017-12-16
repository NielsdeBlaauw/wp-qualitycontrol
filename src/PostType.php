<?php

namespace NDB\QualityControl;

use NDB\QualityControl\FieldTypes\Image;

class PostType implements iContext{
  public $post_type = null;
  public $process_order = 10;
  public function __construct(\WP_Post_Type $post_type, Generator $generator){
    $this->generator = $generator;
    $this->post_type = $post_type;
    $this->process_order = $generator->config->get("post_types.{$this->post_type->name}.process_order", 10);
  }

  public function generate(){
    $image_provider = new Image(array(), $this);
    $post_title_max_length = apply_filters('ndb/qualitycontrol/post_title', 20);
    $post_title_max_length = apply_filters('ndb/qualitycontrol/post_title/post_type='.$this->post_type->name, $post_title_max_length);
    $parent = 0;
    if($this->post_type->hierarchical && round(rand(0,1))){
      $parentOption = get_posts(array(
        'orderby'=>'rand',
        'fields'=>'ids',
        'post_type'=>$this->post_type->name,
        'posts_per_page'=>1
      ));
      if(!empty($parentOption)){
        $parent = array_pop($parentOption);
      }
    }
    $post_id = wp_insert_post(array(
      'post_title'=>$this->generator->faker->sentence($this->generator->faker->numberBetween(1, $post_title_max_length)),
      'post_type'=>$this->post_type->name,
      'post_status'=>'publish',
      'post_parent'=>$parent,
      'post_excerpt'=>$this->generator->faker->words($this->generator->faker->numberBetween(0,55), true),
      'post_content'=>$this->generator->faker->paragraphs($this->generator->faker->randomDigit, true),
      'post_date'=>$this->generator->faker->optional->iso8601(),
      'post_modified'=>$this->generator->faker->optional->iso8601(),
      'meta_input'=>array(
        Generator::META_IDENTIFIER_KEY=>'1',
      ),
    ));
    set_post_thumbnail($post_id, $image_provider->generate($post_id));
    $this->fill_acf_fields($post_id);
  }

  protected function fill_acf_fields(int $post_id){
    $fieldgroups = acf_get_field_groups(array('post_id'=>$post_id));
    foreach($fieldgroups as $fieldgroup){
      $fields = acf_get_fields_by_id($fieldgroup['ID']);
      if(!empty($fields)){
        foreach($fields as $fieldData){
          $field = FieldFactory::create_field($fieldData, $this);
          $field->direct_insert($post_id);
        }
      }
    }
  }
}