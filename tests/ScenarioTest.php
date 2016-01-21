<?php

namespace Aschelch\Scenario\Tests;

use Aschelch\Scenario\Scenario;

class ScenarioTest extends \PHPUnit_Framework_TestCase{

    public function setUp(){
        parent::setUp();

        $this->trigger = \Mockery::mock('Aschelch\Scenario\TriggerInterface');
        $this->action = \Mockery::mock('Aschelch\Scenario\ActionInterface');
    }

    public function testScenarioIsTriggered() {
        $this->trigger->shouldReceive('isTriggered')->with('some params')->once()->andReturn(true);
        $scenario = new Scenario($this->trigger, $this->action);
        $this->assertTrue($scenario->isTriggered('some params'));
    }

    public function testScenarioIsExecuted() {
        $this->action->shouldReceive('execute')->with('some params')->once()->andReturn(true);
        $scenario = new Scenario($this->trigger, $this->action);
        $this->assertTrue($scenario->execute('some params'));
    }


}
