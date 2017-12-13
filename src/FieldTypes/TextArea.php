<?php

namespace NDB\QualityControl\FieldTypes;

class TextArea extends Base implements iFieldType{
  public function generate($post_id){
    return substr($this->faker->paragraphs(10, true), $this->get_min(), $this->get_max());
  }
}
