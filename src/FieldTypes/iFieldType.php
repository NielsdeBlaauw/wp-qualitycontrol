<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;

interface iFieldType{
  public function __construct(\NDB\QualityControl\FieldDefinitions\FieldDefinition $field, iContext $context);
  public function generate($post_id);
  public function direct_insert($post_id);
}