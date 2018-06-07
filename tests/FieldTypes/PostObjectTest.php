<?php

class acf_field_post_object{
    public function get_ajax_query(){
        return array(
            'results'=>array(
                array(
                    'id'=>5
                )
            )
        );
    }
}

class PostObjectTest extends \WP_Mock\Tools\TestCase {
	public function setUp() {
		\WP_Mock::setUp();
    }

    public function testMapsToCorrectField(){
        $fieldData = array(
            'type'=>'post_object',
            'key'=>'key',
            'name'=>'testTextField'
        );
        $primitive = new \NDB\QualityControl\FieldDefinitions\ACF($fieldData);
        $context = $this->createMock('\NDB\QualityControl\iContext');
        $field = \NDB\QualityControl\FieldFactory::create_field($primitive, $context);
        $this->assertInstanceOf('\NDB\QualityControl\FieldTypes\PostObject', $field);
        return $field;
    }
    
    /**
     * @depends testMapsToCorrectField
     */
    public function testCanGenerate($field){
        \WP_Mock::userFunction( 'wp_cache_get' )->once()
                  ->andReturn( false );

        \WP_Mock::userFunction( 'wp_cache_set' )->once()
                  ->andReturn( true );
                  
        $field->generate(0);
        $this->assertConditionsMet();
    }

	public function tearDown() {
        \WP_Mock::tearDown();
        \Mockery::close();
	}
}