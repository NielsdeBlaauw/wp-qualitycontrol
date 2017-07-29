<?php

namespace NDB\QualityControl\FieldTypes;

class Select extends Base implements iFieldType{
  public function generate(int $post_id){
    $keys = array_keys($this->field['choices']);
    $max_to_select = 1;
    if($this->field['multiple']){
      $max_to_select = count($keys);
    }
    $min_to_select = 0;
    if($this->field['required']){
      $min_to_select = 1;
    }
    return $this->faker->randomElements($keys, $this->faker->numberBetween($min_to_select, $max_to_select));
  }
}
