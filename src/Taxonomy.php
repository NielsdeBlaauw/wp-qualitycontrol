<?php

namespace NDB\QualityControl;

use NDB\QualityControl\FieldTypes\Image;

class Taxonomy implements iContext{
  public $post_type = null;
  public $process_order = 200;
  public function __construct(\WP_Taxonomy $taxonomy, Generator $generator){
    $this->generator = $generator;
    $this->taxonomy = $taxonomy;
    $this->process_order = $generator->config->get("taxonomies.{$this->taxonomy->name}.process_order", 200);
    $this->nb_posts = $generator->config->get("taxonomies.{$this->taxonomy->name}.nb_posts", 5);
    $this->faker = \Faker\Factory::create();
  }

  public function get_name() : string{
    return 'Taxonomy ' . $this->taxonomy->name;
  }

  public function generate(){
    $parent = 0;
    if($this->taxonomy->hierarchical && round(rand(0,1))){
      $terms = get_terms( array(
          'taxonomy' => $this->taxonomy->name,
          'hide_empty' => false,
      ) );
      if(!empty($terms)){
        $parent = $this->faker->randomElement($terms);
        $parent = $parent->term_id;
      }
    }
    $term_id = wp_insert_term($this->faker->text($this->generator->config->get("taxonomies.{$this->taxonomy->name}.title_length", 50)), $this->taxonomy->name, array(
      'description'=>$this->faker->paragraphs(2, true),
      'parent'=>$parent
    ));
    $term_id = $term_id['term_id'];
    update_term_meta($term_id, Generator::META_IDENTIFIER_KEY, '1');

    $this->fill_acf_fields($term_id);
    $this->fill_custom_fields($term_id);
  }

  public static function clean(){
    $terms = get_terms(array(
      'meta_key'=>Generator::META_IDENTIFIER_KEY,
      'meta_value'=>'1',
      'hide_empty'=>false
    ));
    $progress = \WP_CLI\Utils\make_progress_bar( 'Cleaning generated terms', count($terms) );
    foreach($terms as $term){
      wp_delete_term($term->term_id, $term->taxonomy);
      $progress->tick();
    }
    $progress->finish();
  }

  public function insert_meta(int $id, $key, $value){
    update_term_meta($id, $key, $value);
  }

  protected function fill_custom_fields($term_id){
    $fields = $this->generator->config->get("taxonomies.{$this->taxonomy->name}.fields", array());
    if(!empty($fields)){
      foreach($fields as $fieldData){
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\Custom($fieldData);
        $field = FieldFactory::create_field($fieldDefinition, $this);
        $field->custom_meta_insert($term_id);
      }
    }
  }

  protected function fill_acf_fields($term_id){
    $fieldgroups = acf_get_field_groups(array('taxonomy'=>$this->taxonomy->name));
    foreach($fieldgroups as $fieldgroup){
      $fields = acf_get_fields_by_id($fieldgroup['ID']);
      if(!empty($fields)){
        foreach($fields as $fieldData){
          $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($fieldData);
          $field = FieldFactory::create_field($fieldDefinition, $this);
          $field->direct_insert('term_' . $term_id);
        }
      }
    }
  }
}