<?php

namespace NDB\ACFQC\FieldTypes;

class PostObject extends Base implements iFieldType{
  public function generate(int $post_id){
    $field_object = new \acf_field_post_object();
    $results = $field_object->get_ajax_query(array(
      'field_key'=>$this->field['key']
    ));
    $chosenItem = array_rand($results['results']);
    return $results['results'][$chosenItem]['id'];
  }
}