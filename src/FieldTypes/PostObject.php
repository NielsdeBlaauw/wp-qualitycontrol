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
    if(is_array($results['results'])){
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
    }
    $this->ids = $ids;
    $max = $this->get_max();
    if($max == 0){
      \NDB\QualityControl\Command::$warnings[$this->field['key'].'_no_relation_possible'] = sprintf('Custom field %s depends on a different content-type, but could not find correct results. You probably need to edit the process_order for this content-type.', $this->field['key']);
      return array();
    }
    $elements = $this->faker->randomElements($ids, $this->faker->numberBetween($this->get_min(), $max));
    if(count($elements) > 1){
      return $elements;
    }
    return $elements[0];
  }

  public function custom_meta_insert(int $id){
    \NDB\QualityControl\Command::$warnings[$this->field['key'].'_custom_meta_unsupported'] = sprintf('Custom field %s has unsupported type PostObject.', $this->field['key']);
  }

  public function get_max() : int{
    $max_to_select = 1;
    if(isset($this->field['multiple']) && $this->field['multiple']){
      $max_to_select = parent::get_max();
    }
    return min(count($this->ids), $max_to_select);
  }
}
