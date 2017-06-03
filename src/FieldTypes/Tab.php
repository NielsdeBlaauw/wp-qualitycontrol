<?php

namespace NDB\ACFQC\FieldTypes;
use NDB\ACFQC\PostType;

class Tab extends NotImplementedField implements iFieldType{
  public function __construct(array $field, PostType $post_type){}
}