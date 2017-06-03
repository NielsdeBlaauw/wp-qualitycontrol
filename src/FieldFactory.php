<?php

namespace NDB\ACFQC;
use NDB\ACFQC\FieldTypes\URL;
use NDB\ACFQC\FieldTypes\Tab;
use NDB\ACFQC\FieldTypes\Text;
use NDB\ACFQC\FieldTypes\RichText;
use NDB\ACFQC\FieldTypes\TextArea;
use NDB\ACFQC\FieldTypes\TrueFalse;
use NDB\ACFQC\FieldTypes\FlexibleContent;
use NDB\ACFQC\FieldTypes\NotImplementedField;

class FieldFactory{
  public static function create_field(array $field, PostType $post_type){
    switch($field['type']){
      case 'flexible_content':
        return new FlexibleContent($field, $post_type);
        break;
      case 'tab':
        return new Tab($field, $post_type);
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
      case 'url':
        return new URL($field, $post_type);
        break;
      case 'wysiwyg':
        return new RichText($field, $post_type);
        break;
      default:
        return new NotImplementedField($field, $post_type);
        break;
    }
  }
}