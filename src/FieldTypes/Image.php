<?php

namespace NDB\QualityControl\FieldTypes;

class Image extends Base implements iFieldType{
  public function generate($post_id){
    $images = get_posts(array(
      'post_type'=>'attachment',
      'post_status'=>'any',
      'posts_per_page'=>1,
      'orderby'=>'rand',
      'post_mime_type'=>'image',
      'fields'=>'ids',
    ));
    if(empty($images)){
      return null;
    }
    return $images[0];
  }
}