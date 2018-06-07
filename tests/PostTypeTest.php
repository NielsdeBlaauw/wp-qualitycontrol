<?php

class PostTypeTest extends \WP_Mock\Tools\TestCase {
	public function setUp() {
		\WP_Mock::setUp();
    }
    
    public function testCanGenerate(){
        $post_type = Mockery::mock( '\WP_Post_Type' );
        $post_type->name = 'test';
        $post_type->hierarchical = false;

        \WP_Mock::userFunction( 'wp_insert_post' )->once()
                  ->andReturn( true );
        
        \WP_Mock::userFunction( 'set_post_thumbnail' )->once()
                  ->andReturn( true );

        \WP_Mock::userFunction( 'get_posts' )->once()
                  ->andReturn( array(1) );
        
        \WP_Mock::userFunction( 'acf_get_field_groups' )->once()
                  ->andReturn( array(1) );

        \WP_Mock::userFunction( 'acf_get_fields_by_id' )->once()
                  ->andReturn( array(array('name'=>'test', 'type'=>'text', 'key'=>'1')) );

        \WP_Mock::userFunction( 'update_field' )->once()
                  ->andReturn( true );

        $context = new \NDB\QualityControl\PostType($post_type);
        $context->generate();
        $this->assertConditionsMet();
    }

	public function tearDown() {
        \WP_Mock::tearDown();
        \Mockery::close();
	}
}