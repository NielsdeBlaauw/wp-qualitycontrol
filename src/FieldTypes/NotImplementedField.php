<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;

class NotImplementedField extends Base implements iFieldType{
  public function __construct(\NDB\QualityControl\FieldDefinitions\FieldDefinition $field, iContext $context){
    parent::__construct($field, $context);
    do_action('\ndb\qualitycontrol\warning', array(
      'name'=>$this->field->get_key().'_implemented', 
      'description'=>sprintf('Field %s of type %s is not yet implemented', $field->get_name(), $field->get_name()))
    );
  }

  public function generate($post_id){}

  public function direct_insert($post_id){}
}
