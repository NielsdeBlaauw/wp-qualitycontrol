<?php

namespace NDB\QualityControl\FieldDefinitions;

class ACF implements FieldDefinition{
    protected $raw = array();

    public function __construct(array $raw){
        $this->raw = $raw;
    }

    public function get_name() : string{
        return $this->raw['name'];
    }

    public function get_key() : string{
        return $this->raw['key'];
    }

    public function get_type() : string{
        return $this->raw['type'];
    }

    public function get_raw() : array{
        return $this->raw;
    }
}