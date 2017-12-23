<?php

namespace NDB\QualityControl\FieldTypes;

class TimePicker extends Base implements iFieldType{
  public function generate($post_id){
    return $this->faker->time();
  }
}
