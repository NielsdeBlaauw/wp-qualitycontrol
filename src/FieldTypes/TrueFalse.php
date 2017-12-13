<?php

namespace NDB\QualityControl\FieldTypes;

class TrueFalse extends Base implements iFieldType{
  public function generate($post_id){
    return $this->faker->boolean();
  }
}