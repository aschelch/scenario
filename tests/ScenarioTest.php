<?php

namespace PlumeSolution\Scenario\Tests;

use PlumeSolution\Scenario\Scenario;
use PHPUnit\Framework\TestCase;
use PlumeSolution\Scenario\TriggerInterface;
use PlumeSolution\Scenario\ActionInterface;

class ScenarioTest extends TestCase
{
    /**
     * @var TriggerInterface
     */
    private $trigger;
    /**
     * @var ActionInterface
     */
    private $action;

    public function setUp() : void
    {
        parent::setUp();

        $this->trigger = \Mockery::mock(TriggerInterface::class);
        $this->action = \Mockery::mock(ActionInterface::class);
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
