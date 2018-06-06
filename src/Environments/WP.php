<?php

namespace NDB\QualityControl\Environments;

class WP{
    public function apply_filters(...$args){
        return call_user_func_array('apply_filters', $args);
    }

    public function make_progress_bar($name, $ccount){
        return \WP_CLI\Utils\make_progress_bar($name, $count);
    }
}