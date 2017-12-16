<?php

namespace NDB\QualityControl\FieldTypes;

class PostObject extends Base implements iFieldType{
  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 50;
  protected $ids = array();

  public function generate($post_id){
    $field_object = new \acf_field_post_object();
    return $this->generate_from_field_object($field_object);
  }

  protected function generate_from_field_object($field_object){    
    $results = wp_cache_get('results_'.$this->field['key'], 'wp-qualitycontrol');
    if(empty($results)){
      $results = $field_object->get_ajax_query(array(
        'field_key'=>$this->field['key']
      ));
      wp_cache_set('results_'.$this->field['key'], $results, 'wp-qualitycontrol');
    }
    $ids = array();
    foreach($results['results'] as $result){
      if(isset($result['children'])){
        foreach($result['children'] as $child){
          $ids[] = $child['id'];
        }
      }
      if(isset($result['id'])){
        $ids[] = $result['id'];
      }
    }
    $this->ids = $ids;
    return $this->faker->randomElements($ids, $this->faker->numberBetween($this->get_min(), $this->get_max()));
  }

  public function get_max() : int{
    if(!$this->field['multiple']){
      return 1;
    }
    $max_to_select = parent::get_max();
    return min(count($this->ids), $max_to_select);
  }
}
