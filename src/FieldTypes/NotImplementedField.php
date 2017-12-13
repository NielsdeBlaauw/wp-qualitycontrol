<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;

class NotImplementedField extends Base implements iFieldType{
  public function __construct(array $field, iContext $context){
    parent::__construct($field, $context);
    \NDB\QualityControl\Command::$warnings[$this->field['key'].'_implemented'] = sprintf('Field %s of type %s is not yet implemented', $field['name'], $field['type']);
  }

  public function generate($post_id){}

  public function direct_insert($post_id){}
}
