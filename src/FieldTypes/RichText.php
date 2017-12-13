<?php

namespace NDB\QualityControl\FieldTypes;

class RichText extends Base implements iFieldType{
  public function generate($post_id){
    // @TODO: Replace with faker HTMLLorem provider when available
    return substr($this->faker->paragraphs(10, true), $this->get_min(), $this->get_max());
  }
}
