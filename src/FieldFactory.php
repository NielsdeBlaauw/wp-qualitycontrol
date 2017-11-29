<?php

namespace NDB\QualityControl;
use NDB\QualityControl\FieldTypes\URL;
use NDB\QualityControl\FieldTypes\Tab;
use NDB\QualityControl\FieldTypes\Message;
use NDB\QualityControl\FieldTypes\Text;
use NDB\QualityControl\FieldTypes\Repeater;
use NDB\QualityControl\FieldTypes\RichText;
use NDB\QualityControl\FieldTypes\Image;
use NDB\QualityControl\FieldTypes\Select;
use NDB\QualityControl\FieldTypes\TextArea;
use NDB\QualityControl\FieldTypes\TrueFalse;
use NDB\QualityControl\FieldTypes\PostObject;
use NDB\QualityControl\FieldTypes\FlexibleContent;
use NDB\QualityControl\FieldTypes\ColorPicker;
use NDB\QualityControl\FieldTypes\Radio;
use NDB\QualityControl\FieldTypes\Number;
use NDB\QualityControl\FieldTypes\PageLink;
use NDB\QualityControl\FieldTypes\OEmbed;
use NDB\QualityControl\FieldTypes\Relationship;
use NDB\QualityControl\FieldTypes\NotImplementedField;

class FieldFactory{
  public static function create_field(array $field, PostType $post_type){
    if($customFieldName = apply_filters('ndb/qualitycontrol/field_name=' . $field['name'], false, $field, $post_type)){
      return $customFieldName;
    }
    if($customFieldKey = apply_filters('ndb/qualitycontrol/field_key=' . $field['key'], false, $field, $post_type)){
      return $customFieldKey;
    }
    if($customFieldType = apply_filters('ndb/qualitycontrol/field_type=' . $field['type'], false, $field, $post_type)){
      return $customFieldType;
    }

    switch($field['type']){
      case 'flexible_content':
        return new FlexibleContent($field, $post_type);
        break;
      case 'tab':
        return new Tab($field, $post_type);
        break;
      case 'message':
        return new Message($field, $post_type);
        break;
      case 'text':
        return new Text($field, $post_type);
        break;
      case 'textarea':
        return new TextArea($field, $post_type);
        break;
      case 'true_false':
        return new TrueFalse($field, $post_type);
        break;
      case 'color_picker':
        return new ColorPicker($field, $post_type);
        break;
      case 'url':
        return new URL($field, $post_type);
        break;
      case 'wysiwyg':
        return new RichText($field, $post_type);
        break;
      case 'select':
        return new Select($field, $post_type);
        break;
      case 'repeater':
        return new Repeater($field, $post_type);
        break;
      case 'image':
        return new Image($field, $post_type);
        break;
      case 'post_object':
        return new PostObject($field, $post_type);
        break;
      case 'radio':
        return new Radio($field, $post_type);
        break;
      case 'oembed':
        return new OEmbed($field, $post_type);
        break;
      case 'number':
        return new Number($field, $post_type);
        break;
      case 'page_link':
        return new PageLink($field, $post_type);
        break;
      case 'relationship':
        return new Relationship($field, $post_type);
        break;
      default:
        return new NotImplementedField($field, $post_type);
        break;
    }
  }
}
