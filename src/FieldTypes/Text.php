<?php

namespace NDB\QualityControl\FieldTypes;

class Text extends Base implements iFieldType{
  public function generate(int $post_id){
    return $this->post_type->generator->faker->text($this->post_type->generator->faker->numberBetween(5, $this->get_max()));
  }
}
