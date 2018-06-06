<?php

class GeneratorTest extends \PHPUnit\Framework\TestCase{
    public function testCanCreateGenerator(){
        $context = $this->createMock('\NDB\QualityControl\iContext');
        $contextMapper = $this->createMock('\NDB\QualityControl\ContextMappers\ContextMapper');
        $contextMapper->method('map')
             ->willReturn(array($context));
        $generator = new \NDB\QualityControl\Generator(array($contextMapper));
        $this->assertInstanceOf('\NDB\QualityControl\Generator', $generator);
        $this->assertNotEmpty($generator->contexts);
        return $generator;
    }

    /**
     * @depends testCanCreateGenerator
     */
    public function testCanGenerate($generator){
        \NDB\QualityControl\Environment::$instance = $this->createMock('\NDB\QualityControl\Environments\WP');
        \NDB\QualityControl\Environment::$instance->method('make_progress_bar')
            ->willReturn($this->createMock('\NDB\QualityControl\ProgressBar'));

        $context = $this->getMockBuilder('\NDB\QualityControl\iContext')
                        ->setMethods(array('generate', 'get_name', 'clean', 'insert_meta'))
                        ->getMock();
        $context->expects($this->once())->method('generate');
        $context->nb_posts = 1;
        $contextMapper = $this->createMock('\NDB\QualityControl\ContextMappers\ContextMapper');
        $contextMapper->method('map')
             ->willReturn(array($context));
        $generator = new \NDB\QualityControl\Generator(array($contextMapper));
        $generator->generate();
    }
}