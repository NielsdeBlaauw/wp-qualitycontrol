<?php

namespace NDB\ACFQC\FieldTypes;
use NDB\ACFQC\PostType;

class NotImplementedField implements iFieldType{
  public function __construct(array $field, PostType $post_type){
    \NDB\ACFQC\Command::$warnings[] = sprintf('Field %s of type %s is not yet implemented', $field['name'], $field['type']);
  }

  public function generate(int $post_id){}

  public function direct_insert(int $post_id){}
}