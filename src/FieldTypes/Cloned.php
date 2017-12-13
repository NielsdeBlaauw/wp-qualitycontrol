<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\PostType;
use NDB\QualityControl\FieldFactory;

class Cloned extends Base implements iFieldType{

  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 65535;

  public function __construct(array $field, PostType $post_type){
    parent::__construct($field, $post_type);
    $this->parse_sub_fields();
  }

  protected function parse_sub_fields(){
    $this->sub_fields = array();
    foreach($this->field['sub_fields'] as $sub_field_data){
      $sub_field_data['key'] = $sub_field_data['__key'];
      $this->sub_fields[$sub_field_data['name']] = FieldFactory::create_field($sub_field_data, $this->post_type);
    }
  }

  public function generate(int $post_id){}

  public function direct_insert(int $post_id){
    foreach($this->sub_fields as $field){
      $field->direct_insert($post_id);
    }
  }
}
