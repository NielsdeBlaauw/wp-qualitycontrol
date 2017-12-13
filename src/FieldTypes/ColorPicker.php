<?php

namespace NDB\QualityControl\FieldTypes;

class ColorPicker extends Base implements iFieldType{
  public function generate($post_id){
    return $this->faker->hexcolor;
  }
}
