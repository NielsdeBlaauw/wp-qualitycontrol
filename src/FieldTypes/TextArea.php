<?php

namespace NDB\QualityControl\FieldTypes;

class TextArea extends Base implements iFieldType{
  public function generate(int $post_id){
    return substr($this->post_type->generator->faker->paragraphs(10, true), 0, $this->get_max());
  }
}
