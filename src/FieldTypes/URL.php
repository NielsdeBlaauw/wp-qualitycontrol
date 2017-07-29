<?php

namespace NDB\QualityControl\FieldTypes;

class URL extends Base implements iFieldType{
  public function generate(int $post_id){
    return $this->post_type->generator->faker->url;
  }
}
