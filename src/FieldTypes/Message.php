<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\PostType;

class Message extends NotImplementedField implements iFieldType{
  public function __construct(array $field, PostType $post_type){}
}