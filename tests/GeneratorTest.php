<?php

class GeneratorTest extends \WP_Mock\Tools\TestCase {
	public function setUp() {
		\WP_Mock::setUp();
    }

    public function testCanCreateGenerator(){
        $progress = Mockery::Mock();
        $progress->shouldReceive('tick')->times(15);
        $progress->shouldReceive('finish')->times(3);

        $context = Mockery::Mock('\NDB\QualityControl\iContext');
        $context->nb_posts = 5;
        $context->process_order = 5;
        $context->shouldReceive('get_name')->andReturn('Test');
        $context->shouldReceive('generate');
        
        $context2 = clone $context;
        $context2->process_order = 15;

        $context3 = clone $context;
        $context3->process_order = 15;

        $contextMapper = Mockery::mock( '\NDB\QualityControl\ContextMappers\ContextMapper' );
        $contextMapper->shouldReceive('map')->once()->andReturn(array($context, $context2, $context3));

        \WP_Mock::userFunction( 'WP_CLI\Utils\make_progress_bar' )->times(3)
                  ->andReturn( $progress );

        $generator = new \NDB\QualityControl\Generator(array($contextMapper));
        $this->assertInstanceOf('\NDB\QualityControl\Generator', $generator);
        $generator->generate();
    }

    public function tearDown() {
        \WP_Mock::tearDown();
        \Mockery::close();
	}
}