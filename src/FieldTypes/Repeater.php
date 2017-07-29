<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\PostType;
use NDB\QualityControl\FieldFactory;

class Repeater extends Base implements iFieldType{
  public $sub_fields = array();

  public function __construct(array $field, PostType $post_type){
    parent::__construct($field, $post_type);
    $this->parse_sub_fields();
  }

  public function parse_sub_fields(){
    $this->sub_fields = array();
    foreach($this->field['sub_fields'] as $sub_field_data){
      $this->sub_fields[$sub_field_data['name']] = FieldFactory::create_field($sub_field_data, $this->post_type);
    }
  }

  public function generate(int $post_id){
    $rows = array();
    $repeater_rows = $this->post_type->generator->faker->numberBetween($this->get_min(), $this->get_max());
    for($row_count = 0; $row_count < $repeater_rows; $row_count += 1){
      $row = array();
      foreach($this->sub_fields as $sub_field){
        $row[$sub_field->field['key']] = $sub_field->generate($post_id);
      }
      $rows[] = $row;
    }
    return $rows;
  }

  public function direct_insert(int $post_id){
    update_field($this->field['key'], $this->generate($post_id), $post_id);
  }

  public function get_min() : int{
    if(empty($this->field['min'])){
      if($this->field['required']){
        return 1;
      }
      return 0;
    }
    return (int) $this->field['min'];
  }

  public function get_max() : int{
    if(empty($this->field['max'])){
      \NDB\QualityControl\Command::$warnings[$this->field['key'].'_max_length'] = sprintf('No max length set for field %s. Falling back to default %d', $this->field['name'], 50);
      return 50;
    }
    return (int) $this->field['max'];
  }
}
