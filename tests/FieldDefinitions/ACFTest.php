<?php

class ACFTest extends \WP_Mock\Tools\TestCase {
	public function setUp() {
		\WP_Mock::setUp();
    }
    
    public function testCanGenerate(){
        $data = array();
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertInstanceOf('\NDB\QualityControl\FieldDefinitions\ACF', $fieldDefinition);
    }

    /**
     * @depends testCanGenerate
     */
    public function testCanGetName(){
        $data = array('name'=>'test');
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertEquals('test', $fieldDefinition->get_name());
    }

    /**
     * @depends testCanGenerate
     */
    public function testCanGetKey(){
        $data = array('key'=>'test');
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertEquals('test', $fieldDefinition->get_key());
    }

    /**
     * @depends testCanGenerate
     */
    public function testCanGetType(){
        $data = array('type'=>'test');
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertEquals('test', $fieldDefinition->get_type());
    }

    /**
     * @depends testCanGenerate
     */
    public function testCanGetMin(){
        $data = array('min'=>1);
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertEquals(1, $fieldDefinition->get_min('min'));
        $this->assertEquals(0, $fieldDefinition->get_min('not_min'));
    }

    /**
     * @depends testCanGenerate
     */
    public function testCanGetMax(){
        $data = array('max'=>20);
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertEquals(20, $fieldDefinition->get_max('max'));
        $this->assertEquals(0, $fieldDefinition->get_min('not_max'));
    }

    /**
     * @depends testCanGenerate
     */
    public function testCanGetAllowedMultiple(){
        $data = array();
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertFalse($fieldDefinition->allow_multiple());

        $data = array('multiple'=>true);
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertTrue($fieldDefinition->allow_multiple());
    }

    
    /**
     * @depends testCanGenerate
     */
    public function testCanGetisRequired(){
        $data = array();
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertFalse($fieldDefinition->is_required());

        $data = array('required'=>true);
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($data);
        $this->assertTrue($fieldDefinition->is_required());
    }

    

	public function tearDown() {
        \WP_Mock::tearDown();
        \Mockery::close();
	}
}