<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;
use NDB\QualityControl\FieldFactory;

class Cloned extends Base implements iFieldType{

  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 65535;

  public function __construct(\NDB\QualityControl\FieldDefinitions\FieldDefinition $field, iContext $context){
    parent::__construct($field, $context);
    $this->parse_sub_fields();
  }

  protected function parse_sub_fields(){
    $this->sub_fields = array();
    foreach($this->field['sub_fields'] as $sub_field_data){
      $sub_field_data['key'] = $sub_field_data['__key'];
      $sub_field = new \NDB\QualityControl\FieldDefinitions\ACF($sub_field_data);
      $this->sub_fields[$sub_field_data['name']] = FieldFactory::create_field($sub_field, $this->context);
    }
  }

  public function custom_meta_insert(int $id){
    \NDB\QualityControl\Command::$warnings[$this->field['key'].'_custom_meta_unsupported'] = sprintf('Custom field %s has unsupported type Cloned.', $this->field['key']);
  }

  public function generate($post_id){}

  public function direct_insert($post_id){
    foreach($this->sub_fields as $field){
      $field->direct_insert($post_id);
    }
  }
}
