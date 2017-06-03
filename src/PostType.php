<?php

namespace NDB\ACFQC;

use NDB\ACFQC\FieldTypes\Image;

class PostType{
  public $post_type = null;
  public function __construct(\WP_Post_Type $post_type, Generator $generator){
    $this->generator = $generator;
    $this->post_type = $post_type;
  }

  public function generate(){
    $image_provider = new Image(array(), $this);
    $post_id = wp_insert_post(array(
      'post_title'=>$this->generator->faker->sentence($this->generator->faker->numberBetween(1,200)),
      'post_type'=>$this->post_type->name,
      'post_status'=>'publish',
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
      foreach($fields as $fieldData){
        $field = FieldFactory::create_field($fieldData, $this);
        $field->direct_insert($post_id);
      }
    }
  }
}