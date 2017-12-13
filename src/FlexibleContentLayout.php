<?php

namespace NDB\QualityControl;
use NDB\QualityControl\FieldTypes\FlexibleContent;

class FlexibleContentLayout{
  public $layout = array();
  public $fields = array();
  public function __construct(array $layout_data, FlexibleContent $parent){
    $this->layout = $layout_data;
    $this->parent = $parent;
    $this->parse_fields();
  }

  public function parse_fields(){
    $this->fields = array();
    foreach($this->layout['sub_fields'] as $field_data){
      $this->fields[] = FieldFactory::create_field($field_data, $this->parent->context);
    }
  }

  public function generate($post_id){
    $field_content = array(
      "acf_fc_layout" => $this->layout['name'],
    );
    foreach($this->fields as $field){
      $field_content[$field->field['key']] = $field->generate($post_id);
    }
    return $field_content;
  }
}