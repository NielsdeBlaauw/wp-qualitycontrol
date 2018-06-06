<?php

namespace NDB\QualityControl\ContextMappers;

class OptionsPage{
    public function map() : array{
        return array(new \NDB\QualityControl\OptionsPage());
    }
}