<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\PostType;

class NotImplementedField implements iFieldType{
  public function __construct(array $field, PostType $post_type){
    \NDB\QualityControl\Command::$warnings[] = sprintf('Field %s of type %s is not yet implemented', $field['name'], $field['type']);
  }

  public function generate(int $post_id){}

  public function direct_insert(int $post_id){}
}