<?php

namespace NDB\QualityControl\FieldTypes;
use NDB\QualityControl\PostType;
use NDB\QualityControl\FlexibleContentLayout;

class FlexibleContent extends Base implements iFieldType{
  public $layouts = array();

  protected $min_field = 'min';
  protected $max_field = 'max';
  protected $max_default = 50;

  public function __construct(array $field, PostType $post_type){
    parent::__construct($field, $post_type);
    $this->parse_layouts();
  }

  public function parse_layouts(){
    $this->layouts = array();
    foreach($this->field['layouts'] as $layout_data){
      $this->layouts[$layout_data['name']] = new FlexibleContentLayout($layout_data, $this);
    }
  }

  public function generate(int $post_id){
    $layouts = array();
    $layout_rows = $this->post_type->generator->faker->numberBetween($this->get_min(), $this->get_max());
    for($row_count = 0; $row_count < $layout_rows; $row_count += 1){
      $layouts[] = $this->post_type->generator->faker->randomElement($this->layouts)->generate($post_id);
    }
    return $layouts;
  }

  public function direct_insert(int $post_id){
    update_field($this->field['key'], $this->generate($post_id), $post_id);
  }
}
