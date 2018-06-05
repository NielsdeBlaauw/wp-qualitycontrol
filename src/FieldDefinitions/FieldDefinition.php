<?php

namespace NDB\QualityControl\FieldDefinitions;

interface FieldDefinition{
    public function get_name() : string;
    public function get_key() : string;
    public function get_type() : string;
    public function get_raw() : array;
}