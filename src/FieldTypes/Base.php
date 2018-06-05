<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;

abstract class Base{
  protected $min_field = 'minlength';
  protected $max_field = 'maxlength';
  protected $max_default = 3000;

  public function __construct(array $field, iContext $context){
    $this->field = $field;
    $this->context = $context;
    //$this->faker = $this->context->generator->faker;
  }

  public function direct_insert($id){
    update_field($this->field['key'], $this->generate($id), $id);
  }

  public function get_min() : int{
    if(empty($this->field[$this->min_field])){
      if($this->field['required']){
        return 1;
      }
      return 0;
    }
    return (int) $this->field[$this->min_field];
  }

  public function custom_meta_insert(int $id){
    $this->context->insert_meta($id, $this->field['key'], $this->generate($id));
  }

  public function get_max() : int{
    if(empty($this->field[$this->max_field])){
      \NDB\QualityControl\Command::$warnings[$this->field['key'].'_max_length'] = sprintf('No max length set for field %s. Falling back to default %d', $this->field['name'], $this->max_default);
      return $this->max_default;
    }
    return (int) $this->field[$this->max_field];
  }
}
