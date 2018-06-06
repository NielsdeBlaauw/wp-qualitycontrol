<?php

namespace NDB\QualityControl\ContextMappers;

class PostTypes{
    public function map() : array{
        $skippable_types = array('attachment');
        $built_in_post_types = get_post_types(array(
          'public'=>true,
          '_builtin'=>true,
        ), 'objects');
        $custom_post_types = get_post_types(array(
          'public'=>true,
          '_builtin'=>false,
        ), 'objects');
        $post_types = array_merge($built_in_post_types, $custom_post_types);
        $contexts = array();
        foreach($post_types as $post_type){
          if(!in_array($post_type->name, $skippable_types)){
            $contexts[] = new \NDB\QualityControl\PostType($post_type);
          }
        }
        return $contexts;
    }
}