<?php

class ClonedTest extends \WP_Mock\Tools\TestCase {
	public function setUp() {
		\WP_Mock::setUp();
    }

    public function testCanCreateGenerator(){
        $fieldData = array(
            'type'=>'clone',
            'key'=>'key',
            'name'=>'testCloneField',
            'sub_fields'=>array(array(
                'type'=>'text',
                '__key'=>'key',
                'name'=>'text',
            ))
        );
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($fieldData);
        $context = \Mockery::mock('\NDB\QualityControl\iContext');
        $cloned = new \NDB\QualityControl\FieldTypes\Cloned($fieldDefinition, $context);
        $this->assertNotEmpty($cloned->sub_fields);
        return $cloned;
    }

    /**
     * @depends testCanCreateGenerator
     */
    public function testCanInsert($cloned){
        $sub_field = \Mockery::mock('\NDB\QualityControl\FieldTypes\Text');
        $sub_field->shouldReceive('direct_insert')->once();
        $cloned->sub_fields = array($sub_field);
        $cloned->direct_insert(1);
        $this->assertConditionsMet();
    }

    public function tearDown() {
        \WP_Mock::tearDown();
        \Mockery::close();
	}
}