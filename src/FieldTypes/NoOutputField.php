<?php

namespace NDB\QualityControl\FieldTypes;

class NoOutputField extends Base implements iFieldType{
  public function generate($post_id){}

  public function direct_insert($post_id){}
}
