<?php

namespace NDB\QualityControl\FieldTypes;

class TextArea extends Base implements iFieldType{
  public function generate(int $post_id){
    return substr($this->post_type->generator->faker->paragraphs(10, true), $this->get_min(), $this->get_max());
  }
}
