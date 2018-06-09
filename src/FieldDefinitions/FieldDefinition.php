<?php

namespace NDB\QualityControl\FieldDefinitions;

interface FieldDefinition extends \ArrayAccess{
    public function get_name() : string;
    public function get_key() : string;
    public function get_type() : string;
    public function get_sub_fields() : array;
    public function get_raw() : array;
    public function get_min(string $field_name) : int;
    public function get_max(string $field_name) : int;
    public function is_required() : bool;
    public function allow_multiple() : bool;
}