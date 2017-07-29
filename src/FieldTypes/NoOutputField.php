<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\PostType;

class NoOutputField extends Base implements iFieldType{
  public function __construct(array $field, PostType $post_type){
    parent::__construct($field, $post_type);
  }

  public function generate(int $post_id){}

  public function direct_insert(int $post_id){}
}
