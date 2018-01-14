<?php

namespace NDB\QualityControl;

interface iContext{
    public function get_name() : string;
    public static function clean();
    public function insert_meta(int $id, $key, $value);
}
