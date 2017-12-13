<?php

namespace NDB\QualityControl\FieldTypes;

class Taxonomy extends PostObject implements iFieldType{
  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 50;
  protected $ids = array();

  public function generate($post_id){
    $field_object = new \acf_field_taxonomy();
    return $this->generate_from_field_object($field_object);
  }
}
