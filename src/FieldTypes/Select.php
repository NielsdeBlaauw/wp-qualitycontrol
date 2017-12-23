<?php

namespace NDB\QualityControl\FieldTypes;

class Select extends Base implements iFieldType{
  public function generate($post_id){
    $keys = array_keys($this->field['choices']);
    return $this->faker->randomElements($keys, $this->faker->numberBetween($this->get_min(), $this->get_max()));
  }

  public function get_max() : int{
    $max_to_select = min(1, count($this->field['choices']));
    if($this->field['multiple']){
      $max_to_select = count($this->field['choices']);
    }
    return $max_to_select;
  }
}
