<?php

namespace NDB\QualityControl\FieldTypes;

class URL extends Base implements iFieldType{
  public function generate($post_id){
    return $this->faker->url;
  }
}
