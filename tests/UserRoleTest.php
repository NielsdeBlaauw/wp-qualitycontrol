<?php

class UserRoleTest extends \WP_Mock\Tools\TestCase {
	public function setUp() {
		\WP_Mock::setUp();
    }
    
    public function testCanGenerate(){
        $user_role = Mockery::mock( '\WP_Role' );
        $user_role->name = 'testRole';

        \WP_Mock::userFunction( 'wp_insert_user' )->once()
                  ->andReturn( true );
        
        \WP_Mock::userFunction( 'update_user_meta' )->once()
                  ->andReturn( true );
        
        \WP_Mock::userFunction( 'acf_get_field_groups' )->once()
                  ->andReturn( array(1) );

        \WP_Mock::userFunction( 'acf_get_fields_by_id' )->once()
                  ->andReturn( array(array('name'=>'test', 'type'=>'text', 'key'=>'1')) );


        $context = new \NDB\QualityControl\UserRole($user_role);
        $context->generate();
        $this->assertConditionsMet();
    }

	public function tearDown() {
        \WP_Mock::tearDown();
        \Mockery::close();
	}
}