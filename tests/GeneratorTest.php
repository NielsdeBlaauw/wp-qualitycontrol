<?php

class GeneratorTest extends \PHPUnit\Framework\TestCase{
    public function testCanCreateGenerator(){
        $generator = new \NDB\QualityControl\Generator();
        $this->assertInstanceOf('\NDB\QualityControl\Generator', $generator);
    }
}