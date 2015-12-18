<?php

namespace Aschelch\Scenario\Tests;

use Aschelch\Scenario\Processor;
use Aschelch\Scenario\Scenario;

class ProcessorTest extends \PHPUnit_Framework_TestCase{

    public function setUp(){
        parent::setUp();

        $this->repository = \Mockery::mock('Aschelch\Scenario\ScenarioRepositoryInterface');
        $this->processor = new Processor($this->repository);
    }

    public function testProcessWithoutAnyScenario() {
        $this->repository->shouldReceive('findAllByEvent')->with('some event')->once()->andReturn(array());

        $result = $this->processor->process('some event');
        $this->assertEquals(0, $result);
    }

    public function testProcessWithAProcessedScenario() {
        $scenario = \Mockery::mock('Aschelch\Scenario\Scenario');
        $scenario->shouldReceive('isTriggered')->withAnyArgs()->once()->andReturn(true);
        $scenario->shouldReceive('execute')->once()->andReturn(true);

        $this->repository->shouldReceive('findAllByEvent')->with('some event')->once()->andReturn(array($scenario));
        $this->processor = new Processor($this->repository);

        $result = $this->processor->process('some event');
        $this->assertEquals(1, $result);
    }

    public function testProcessWithAScenarioThatIsNotTriggered() {
        $scenario = \Mockery::mock('Aschelch\Scenario\Scenario');
        $scenario->shouldReceive('isTriggered')->withAnyArgs()->once()->andReturn(false);
        $scenario->shouldReceive('execute')->never();

        $this->repository->shouldReceive('findAllByEvent')->with('some event')->once()->andReturn(array($scenario));
        $this->processor = new Processor($this->repository);

        $result = $this->processor->process('some event');
        $this->assertEquals(0, $result);
    }

}
