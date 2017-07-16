<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\PostType;

interface iFieldType{
  public function __construct(array $field, PostType $post_type);
  public function generate(int $post_id);
  public function direct_insert(int $post_id);
}