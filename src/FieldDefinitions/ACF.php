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

    
    public function get_min(string $field_name) : int{
        if(!isset($this->raw[$field_name])){
            return 0;
        }
        return (int) $this->raw[$field_name];   
    }
    
    public function get_max(string $field_name) : int{
        if(!isset($this->raw[$field_name])){
            return 0;
        }
        return (int) $this->raw[$field_name];   
    }
    
    public function allow_multiple() : bool{
        if(!isset($this->raw['multiple'])){
            return false;
        }
        return (bool) $this->raw['multiple'];   
    }
    
    public function is_required() : bool{
        if(!isset($this->raw['required'])){
            return false;
        }
        return (bool) $this->raw['required'];
    }
    
    /**
     * @codeCoverageIgnore
     */
    public function get_raw() : array{
        trigger_error('Using raw ACF field data is deprecated', E_USER_DEPRECATED);
        return $this->raw;
    }

    /**
     * @codeCoverageIgnore
     */
    public function offsetSet($offset,$value) {
        if (is_null($offset)) {
            $this->raw[] = $value;
        } else {
            $this->raw[$offset] = $value;
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function offsetExists($offset) {
        trigger_error("Use methods to check field {$offset} existance ", E_USER_DEPRECATED);
        return isset($this->raw[$offset]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function offsetUnset($offset) {
        if ($this->offsetExists($offset)) {
            unset($this->raw[$offset]);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function offsetGet($offset) {
        trigger_error("Use methods to access field setting {$offset}", E_USER_DEPRECATED);
        return $this->offsetExists($offset) ? $this->raw[$offset] : null;
    }
}