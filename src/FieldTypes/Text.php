<?php

namespace NDB\QualityControl\FieldTypes;

class Text extends Base implements iFieldType{
  public function generate($post_id){
    return $this->faker->text($this->faker->numberBetween(5, $this->get_max()));
  }
}
