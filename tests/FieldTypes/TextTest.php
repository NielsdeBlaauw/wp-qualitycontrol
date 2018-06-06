<?php

class TextTest extends \PHPUnit\Framework\TestCase{
    public function testMapsToCorrectField(){
        $fieldData = array(
            'type'=>'text',
            'key'=>'key',
            'name'=>'testTextField'
        );
        $primitive = new \NDB\QualityControl\FieldDefinitions\ACF($fieldData);
        $context = $this->createMock('\NDB\QualityControl\iContext');
        $field = \NDB\QualityControl\FieldFactory::create_field($primitive, $context);
        $this->assertInstanceOf('\NDB\QualityControl\FieldTypes\Text', $field);
        return $field;
    }

    /** 
     * @depends testMapsToCorrectField
    */
    public function testCanGetMinLength($field){
        $this->assertEquals(0, $field->get_min());
    }

    /** 
     * @depends testMapsToCorrectField
    */
    public function testCanFallbackMaxLength($field){
        $this->assertEquals(3000, $field->get_max());
    }
    
    /** 
     * @depends testMapsToCorrectField
    */
    public function testCanGenerateContent($field){
        $this->assertNotEmpty($field->generate(0));
    }
}