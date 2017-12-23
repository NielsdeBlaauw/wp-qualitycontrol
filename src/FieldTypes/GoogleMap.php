<?php

namespace NDB\QualityControl\FieldTypes;

class GoogleMap extends Base implements iFieldType{
  public function generate($post_id){
    return array(
      "address"=>$this->faker->address,
      "lat"=>$this->faker->latitude(),
      "lng"=>$this->faker->longitude()
    );
  }
}
