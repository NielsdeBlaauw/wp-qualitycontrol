<?php

namespace NDB\ACFQC\FieldTypes;
use NDB\ACFQC\PostType;

abstract class Base{
  const MAX_LENGTH_DEFAULT = 3000;

  public function __construct(array $field, PostType $post_type){
    $this->field = $field;
    $this->post_type = $post_type;
  }

  public function direct_insert(int $post_id){
    update_field($this->field['key'], $this->generate($post_id), $post_id);
  }

  public function get_min() : int{
    if(empty($this->field['minLength'])){
      return 1;
    }
    return (int) $this->field['minLength'];
  }

  public function get_max() : int{
    if(empty($this->field['maxlength'])){
      \WP_CLI::warning(sprintf('No max length set for field %s. Falling back to default %d', $this->field['name'], self::MAX_LENGTH_DEFAULT));
      return self::MAX_LENGTH_DEFAULT;
    }
    return (int) $this->field['maxlength'];
  }
}