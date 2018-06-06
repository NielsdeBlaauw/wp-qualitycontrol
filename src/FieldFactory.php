<?php

namespace NDB\QualityControl;
use NDB\QualityControl\FieldTypes\iFieldType;
use NDB\QualityControl\FieldTypes\NotImplementedField;

class FieldFactory{
  public static $fieldMapping = array(
    'textarea'=>'\NDB\QualityControl\FieldTypes\TextArea',
    'url'=>'\NDB\QualityControl\FieldTypes\URL',
    'wysiwyg'=>'\NDB\QualityControl\FieldTypes\RichText',
    'oembed'=>'\NDB\QualityControl\FieldTypes\OEmbed',
    'clone'=>'\NDB\QualityControl\FieldTypes\Cloned',
  );

  public static function create_field(\NDB\QualityControl\FieldDefinitions\FieldDefinition $field, iContext $context): iFieldType{
    $customFieldName = apply_filters('ndb/qualitycontrol/field_name=' . $field->get_name(), false, $field, $context);
    if($customFieldName){
      return $customFieldName;
    }
    $customFieldKey = apply_filters('ndb/qualitycontrol/field_key=' . $field->get_key(), false, $field, $context);
    if($customFieldKey){
      return $customFieldKey;
    }
    $customFieldType = apply_filters('ndb/qualitycontrol/field_type=' . $field->get_type(), false, $field, $context);
    if($customFieldType){
      return $customFieldType;
    }
    if(isset(self::$fieldMapping[$field->get_type()]) && class_exists(self::$fieldMapping[$field->get_type()])){
      $fieldclass = self::$fieldMapping[$field->get_type()];
      return new $fieldclass($field, $context);
    }
    $generatedClassName = self::generate_classname($field->get_type());
    if(class_exists($generatedClassName)){
      return new $generatedClassName($field, $context);
    }

    return new NotImplementedField($field, $context);
  }

  public static function generate_classname(string $fieldtype): string{
    return '\\NDB\\QualityControl\\FieldTypes\\' . preg_replace_callback('/(?:^|_)(.?)/', function($match){
      return strtoupper($match[1]);
    }, $fieldtype); 
  }
}
