<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\iContext;
use NDB\QualityControl\FlexibleContentLayout;

class FlexibleContent extends Base implements iFieldType{
  public $layouts = array();

  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 50;

  public function __construct(array $field, iContext $context){
    parent::__construct($field, $context);
    $this->parse_layouts();
  }

  public function parse_layouts(){
    $this->layouts = array();
    foreach($this->field['layouts'] as $layout_data){
      $this->layouts[$layout_data['name']] = new FlexibleContentLayout($layout_data, $this);
    }
  }

  public function generate($post_id){
    $layouts = array();
    $layout_rows = $this->faker->numberBetween($this->get_min(), $this->get_max());
    for($row_count = 0; $row_count < $layout_rows; $row_count += 1){
      $layouts[] = $this->faker->randomElement($this->layouts)->generate($post_id);
    }
    return $layouts;
  }

  public function direct_insert($post_id){
    update_field($this->field['key'], $this->generate($post_id), $post_id);
  }
}
