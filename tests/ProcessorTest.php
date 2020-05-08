<?php

namespace PlumeSolution\Scenario\Tests;

use PlumeSolution\Scenario\Processor;
use PlumeSolution\Scenario\Scenario;
use \Mockery;
use PHPUnit\Framework\TestCase;
use PlumeSolution\Scenario\ScenarioRepositoryInterface;

class ProcessorTest extends TestCase
{
    private $repository;
    /**
     * @var Processor
     */
    private $processor;

    public function setUp() : void
    {
        parent::setUp();

        $this->repository = Mockery::mock(ScenarioRepositoryInterface::class);
        $this->processor = new Processor($this->repository);
    }

    public function testProcessWithoutAnyScenario() {
        $this->repository->shouldReceive('findAllByEvent')->with('some event')->once()->andReturn(array());

        $result = $this->processor->process('some event');
        $this->assertEquals(0, $result);
    }

    public function testProcessWithAProcessedScenario() {
        $scenario = \Mockery::mock(Scenario::class);
        $scenario->shouldReceive('isTriggered')->withAnyArgs()->once()->andReturn(true);
        $scenario->shouldReceive('execute')->once()->andReturn(true);

        $this->repository->shouldReceive('findAllByEvent')->with('some event')->once()->andReturn(array($scenario));
        $this->processor = new Processor($this->repository);

        $result = $this->processor->process('some event');
        $this->assertEquals(1, $result);
    }

    public function testProcessWithAScenarioThatIsNotTriggered() {
        $scenario = Mockery::mock(Scenario::class);
        $scenario->shouldReceive('isTriggered')->withAnyArgs()->once()->andReturn(false);
        $scenario->shouldReceive('execute')->never();

        $this->repository->shouldReceive('findAllByEvent')->with('some event')->once()->andReturn(array($scenario));
        $this->processor = new Processor($this->repository);

        $result = $this->processor->process('some event');
        $this->assertEquals(0, $result);
    }

}
