<?php

namespace NDB\QualityControl\ContextMappers;

class Taxonomies{
    public function map() : array{
        $contexts = array();
        $taxonomies = get_taxonomies(array(), 'objects');
        foreach($taxonomies as $taxonomy){
            $contexts[] = new \NDB\QualityControl\Taxonomy($taxonomy);
        }
        return $contexts;
    }
}