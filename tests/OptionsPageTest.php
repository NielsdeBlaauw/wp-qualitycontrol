<?php

class OptionsPageTest extends \WP_Mock\Tools\TestCase {
	public function setUp() {
		\WP_Mock::setUp();
    }
    
    public function testCanGenerate(){
        \WP_Mock::userFunction( 'acf_get_options_pages' )->once()
                  ->andReturn( array(1) );
        
        \WP_Mock::userFunction( 'acf_get_field_groups' )->once()
                  ->andReturn( array(1) );

        \WP_Mock::userFunction( 'acf_get_fields_by_id' )->once()
                  ->andReturn( array(array('name'=>'test', 'type'=>'text', 'key'=>'1')) );

        \WP_Mock::userFunction( 'update_field' )->once()
                  ->andReturn( true );

        $context = new \NDB\QualityControl\OptionsPage();
        $context->generate();
        $this->assertConditionsMet();
    }

    public function testDoestNotFailWithoutOptionsPages(){
        \WP_Mock::userFunction( 'acf_get_options_pages' )->once()
                  ->andReturn( array() );
        

        $context = new \NDB\QualityControl\OptionsPage();
        $context->generate();
        $this->assertConditionsMet();
    }

	public function tearDown() {
        \WP_Mock::tearDown();
        \Mockery::close();
	}
}