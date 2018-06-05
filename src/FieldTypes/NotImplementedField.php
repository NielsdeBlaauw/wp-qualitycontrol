<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;

class NotImplementedField extends Base implements iFieldType{
  public function __construct(array $field, iContext $context){
    parent::__construct($field, $context);
    do_action('\ndb\qualitycontrol\warning', array(
      'name'=>$this->field['key'].'_implemented', 
      'description'=>sprintf('Field %s of type %s is not yet implemented', $field['name'], $field['type']))
    );
  }

  public function generate($post_id){}

  public function direct_insert($post_id){}
}
