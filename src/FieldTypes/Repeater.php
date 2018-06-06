<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;
use NDB\QualityControl\FieldFactory;

class Repeater extends Base implements iFieldType{
  public $sub_fields = array();

  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 50;

  public function __construct(\NDB\QualityControl\FieldDefinitions\FieldDefinition $field, iContext $context){
    parent::__construct($field, $context);
    $this->parse_sub_fields();
  }

  public function parse_sub_fields(){
    $this->sub_fields = array();
    foreach($this->field['sub_fields'] as $sub_field_data){
      $sub_field = new \NDB\QualityControl\FieldDefinitions\ACF($sub_field_data);
      $this->sub_fields[$sub_field_data['name']] = FieldFactory::create_field($sub_field, $this->context);
    }
  }

  public function generate($post_id){
    $rows = array();
    $repeater_rows = $this->faker->numberBetween($this->get_min(), $this->get_max());
    for($row_count = 0; $row_count < $repeater_rows; $row_count += 1){
      $row = array();
      foreach($this->sub_fields as $sub_field){
        $row[$sub_field->field['key']] = $sub_field->generate($post_id);
      }
      $rows[] = $row;
    }
    return $rows;
  }

  public function direct_insert($post_id){
    update_field($this->field['key'], $this->generate($post_id), $post_id);
  }
}
