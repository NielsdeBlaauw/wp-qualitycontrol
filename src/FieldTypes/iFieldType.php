<?php

namespace NDB\ACFQC\FieldTypes;
use NDB\ACFQC\PostType;

interface iFieldType{
  public function __construct(array $field, PostType $post_type);
  public function generate(int $post_id);
  public function direct_insert(int $post_id);
}