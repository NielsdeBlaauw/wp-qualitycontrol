<?php

namespace NDB\QualityControl\FieldTypes;

class Link extends Base implements iFieldType{
  public function generate($post_id){
    return array(
        "title"=>$this->faker->text($this->faker->numberBetween(5, 100)),
        "url"=>$this->faker->url,
        "target"=>$this->faker->randomElement(array("", "_blank"))
    );
  }
}
