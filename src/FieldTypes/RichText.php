<?php

namespace NDB\QualityControl\FieldTypes;

class RichText extends Base implements iFieldType{
  public function generate(int $post_id){
    // @TODO: Replace with faker HTMLLorem provider when available
    return substr($this->post_type->generator->faker->paragraphs(10, true), $this->get_min(), $this->get_max());
  }
}
