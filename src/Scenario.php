<?php

namespace Aschelch\Scenario;

class Scenario
{
    /**
     * @var TriggerInterface
     */
    private $trigger;
    /**
     * @var ActionInterface
     */
    private $action;

    public function __construct(TriggerInterface $trigger, ActionInterface $action)
    {
        $this->trigger = $trigger;
        $this->action = $action;
    }

    public function getTrigger()
    {
        return $this->trigger;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function isTriggered($params)
    {
        return $this->trigger->isTriggered($params);
    }

    public function execute($params)
    {
        return $this->action->execute($params);
    }
}
