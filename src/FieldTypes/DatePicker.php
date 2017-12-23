<?php

namespace NDB\QualityControl\FieldTypes;

class DatePicker extends Base implements iFieldType{
  public function generate($post_id){
    return $this->faker->date('Ymd');
  }
}
