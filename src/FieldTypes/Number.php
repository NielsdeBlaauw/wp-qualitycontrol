<?php

namespace NDB\QualityControl\FieldTypes;

class Number extends Base implements iFieldType{

  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 65535;

  public function generate(int $post_id){
    return $this->faker->numberBetween($this->get_min(), $this->get_max());
  }
}
