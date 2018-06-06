<?php

class TaxonomyTest extends \WP_Mock\Tools\TestCase {
	public function setUp() {
		\WP_Mock::setUp();
    }
    
    public function testCanGenerate(){
        $taxonomy = Mockery::mock( '\WP_Taxonomy' );
        $taxonomy->name = 'test';
        $taxonomy->hierarchical = false;

        \WP_Mock::userFunction( 'wp_insert_term' )->once()
                  ->andReturn( true );

        \WP_Mock::userFunction( 'update_term_meta' )->once()
                  ->andReturn( true );
        
        \WP_Mock::userFunction( 'acf_get_field_groups' )->once()
                  ->andReturn( array(1) );

        \WP_Mock::userFunction( 'acf_get_fields_by_id' )->once()
                  ->andReturn( array(array('name'=>'test', 'type'=>'text', 'key'=>'1')) );

        \WP_Mock::userFunction( 'update_field' )->once()
                  ->andReturn( true );

        $context = new \NDB\QualityControl\Taxonomy($taxonomy);
        $context->generate();
        $this->assertConditionsMet();
    }

	public function tearDown() {
        \WP_Mock::tearDown();
        \Mockery::close();
	}
}