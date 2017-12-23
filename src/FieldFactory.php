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
use NDB\QualityControl\FieldTypes\Taxonomy;
use NDB\QualityControl\FieldTypes\Cloned;
use NDB\QualityControl\FieldTypes\GoogleMap;
use NDB\QualityControl\FieldTypes\TimePicker;
use NDB\QualityControl\FieldTypes\DatePicker;
use NDB\QualityControl\FieldTypes\NotImplementedField;

class FieldFactory{
  public static function create_field(array $field, iContext $context){
    if($customFieldName = apply_filters('ndb/qualitycontrol/field_name=' . $field['name'], false, $field, $context)){
      return $customFieldName;
    }
    if($customFieldKey = apply_filters('ndb/qualitycontrol/field_key=' . $field['key'], false, $field, $context)){
      return $customFieldKey;
    }
    if($customFieldType = apply_filters('ndb/qualitycontrol/field_type=' . $field['type'], false, $field, $context)){
      return $customFieldType;
    }

    switch($field['type']){
      case 'flexible_content':
        return new FlexibleContent($field, $context);
        break;
      case 'tab':
        return new Tab($field, $context);
        break;
      case 'message':
        return new Message($field, $context);
        break;
      case 'google_map':
        return new GoogleMap($field, $context);
        break;
      case 'text':
        return new Text($field, $context);
        break;
      case 'textarea':
        return new TextArea($field, $context);
        break;
      case 'true_false':
        return new TrueFalse($field, $context);
        break;
      case 'color_picker':
        return new ColorPicker($field, $context);
        break;
      case 'url':
        return new URL($field, $context);
        break;
      case 'wysiwyg':
        return new RichText($field, $context);
        break;
      case 'select':
        return new Select($field, $context);
        break;
      case 'repeater':
        return new Repeater($field, $context);
        break;
      case 'image':
        return new Image($field, $context);
        break;
      case 'post_object':
        return new PostObject($field, $context);
        break;
      case 'radio':
        return new Radio($field, $context);
        break;
      case 'time_picker':
        return new TimePicker($field, $context);
        break;
      case 'date_picker':
        return new DatePicker($field, $context);
        break;
      case 'oembed':
        return new OEmbed($field, $context);
        break;
      case 'number':
        return new Number($field, $context);
        break;
      case 'page_link':
        return new PageLink($field, $context);
        break;
      case 'taxonomy':
        return new Taxonomy($field, $context);
        break;
      case 'clone':
        return new Cloned($field, $context);
        break;
      case 'relationship':
        return new Relationship($field, $context);
        break;
      default:
        return new NotImplementedField($field, $context);
        break;
    }
  }
}
