<?php

namespace NDB\QualityControl\FieldTypes;

class PostObject extends Base implements iFieldType{
  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 50;
  protected $ids = array();

  public function generate(int $post_id){
    $field_object = new \acf_field_post_object();
    $results = $field_object->get_ajax_query(array(
      'field_key'=>$this->field['key']
    ));
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
