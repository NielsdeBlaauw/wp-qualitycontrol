<?php

namespace NDB\QualityControl;

class OptionsPage implements iContext{
    public function __construct(Generator $generator){
        $this->generator = $generator;
        $options_pages = acf_get_options_pages();
        $this->field_groups = array();
        if(!empty($options_pages)){
            foreach($options_pages as $option_page){
                $this->field_groups = acf_get_field_groups(array(
                    'options_page' => $option_page['menu_slug']
                ));
            }
        }
    }

    public function generate(){
        foreach($this->field_groups as $field_group){
            $fields = acf_get_fields_by_id($field_group['ID']);
            if(!empty($fields)){
                foreach($fields as $fieldData){
                    $field = FieldFactory::create_field($fieldData, $this);
                    $field->direct_insert('options');
                }
            }
        }
    }
}
