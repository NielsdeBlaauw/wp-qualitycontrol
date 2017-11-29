<?php

namespace NDB\QualityControl\FieldTypes;

class OEmbed extends Base implements iFieldType{
  public function generate(int $post_id){
    $default_oembed_urls = array(
      'https://www.youtube.com/embed/2ypzpl02Rps',
      'https://www.youtube.com/embed/ygH9VcV7IB',
      'https://soundcloud.com/carbohydromusic/unbreakable-wings'
    );
    $oembed_urls = apply_filters('ndb/qualitycontrol/sources/oembed', $default_oembed_urls);
    return $this->post_type->generator->faker->randomElement($oembed_urls);
  }
}
