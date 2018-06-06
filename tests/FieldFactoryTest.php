<?php

function do_action(...$y){}

if(!class_exists('\WP_CLI_Command')){
    class WP_CLI_Command{}
}

class FieldFactoryTest extends \PHPUnit\Framework\TestCase{
    public function testCanFallBackToNotImplementedField(){
        \NDB\QualityControl\Environment::$instance = $this->createMock('\NDB\QualityControl\Environments\WP');
        $fieldDefinition = $this->createMock('\NDB\QualityControl\FieldDefinitions\ACF');
        $fieldDefinition->method('get_key')
             ->willReturn('NotAFieldKey');
        $fieldDefinition->method('get_name')
             ->willReturn('NotAFieldName');
        $fieldDefinition->method('get_type')
             ->willReturn('NotAFieldType');
             
        $fieldDefinition->method('get_raw')
             ->willReturn(array(
                 'key'=>$fieldDefinition->get_key(),
                 'type'=>$fieldDefinition->get_type(),
                 'name'=>$fieldDefinition->get_name(),
             ));
             
        $context = $this->createMock('\NDB\QualityControl\iContext');
        $field = \NDB\QualityControl\FieldFactory::create_field($fieldDefinition, $context);
        $this->assertInstanceOf('\NDB\QualityControl\FieldTypes\NotImplementedField', $field);
    
    }

    public function testCanGetFieldByNameGuessing(){
        $fieldDefinition = $this->createMock('\NDB\QualityControl\FieldDefinitions\ACF');
        $fieldDefinition->method('get_type')
             ->willReturn('Text');
             
        $context = $this->createMock('\NDB\QualityControl\iContext');
        $field = \NDB\QualityControl\FieldFactory::create_field($fieldDefinition, $context);
        $this->assertInstanceOf('\NDB\QualityControl\FieldTypes\Text', $field);
    }

    public function testCanGetFieldByNameMapping(){
        $fieldDefinition = $this->createMock('\NDB\QualityControl\FieldDefinitions\ACF');
        $fieldDefinition->method('get_type')
             ->willReturn('wysiwyg');
             
        $context = $this->createMock('\NDB\QualityControl\iContext');
        $field = \NDB\QualityControl\FieldFactory::create_field($fieldDefinition, $context);
        $this->assertInstanceOf('\NDB\QualityControl\FieldTypes\RichText', $field);
    }
}