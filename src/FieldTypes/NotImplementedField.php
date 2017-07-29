<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\PostType;

class NotImplementedField extends Base implements iFieldType{
  public function __construct(array $field, PostType $post_type){
    parent::__construct($field, $post_type);
    \NDB\QualityControl\Command::$warnings[$this->field['key']] = sprintf('Field %s of type %s is not yet implemented', $field['name'], $field['type']);
  }

  public function generate(int $post_id){}

  public function direct_insert(int $post_id){}
}
