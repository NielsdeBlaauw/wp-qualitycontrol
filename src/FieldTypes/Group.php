<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;
use NDB\QualityControl\FieldFactory;

class Group extends Base implements iFieldType{
  public $sub_fields = array();

  public function __construct(array $field, iContext $context){
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
    $values = array();
      foreach($this->sub_fields as $sub_field){
        $values[$sub_field->field['key']] = $sub_field->generate($post_id);
      }
    return $values;
  }

  public function direct_insert($post_id){
    update_field($this->field['key'], $this->generate($post_id), $post_id);
  }
}
