<?php

namespace NDB\QualityControl;

class OptionsPage implements iContext{
    public function __construct(){
        $this->process_order = \NDB\QualityControl\Configuration::get_instance()->get("options_page.process_order", 900);
        $this->nb_posts = 1;
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

    public function get_name() : string{
        return 'Options page';
    }

    public function insert_meta(int $id, $key, $value){
        update_site_option($key, $value);
    }

    protected function fill_custom_fields(){
        $fields = \NDB\QualityControl\Configuration::get_instance()->get("options_page.fields", array());
        if(!empty($fields)){
            foreach($fields as $fieldData){
                $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\Custom($fieldData);
                $field = FieldFactory::create_field($fieldDefinition, $this);
                $field->custom_meta_insert(0);
            }
        }
    }

    public function generate(){
        foreach($this->field_groups as $field_group){
            $fields = acf_get_fields_by_id($field_group['ID']);
            if(!empty($fields)){
                foreach($fields as $fieldData){
                    $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($fieldData);
                    $field = FieldFactory::create_field($fieldDefinition, $this);
                    $field->direct_insert('options');
                }
            }
        }
        $this->fill_custom_fields();
    }

    public static function clean(){} // Not implemented on purpose
}
