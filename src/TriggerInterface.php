<?php

namespace PlumeSolution\Scenario;

interface TriggerInterface
{

    /**
     * Check if this instance of trigger is triggered using these event parameters
     *
     * @param  array Event parameters
     * @return boolean True if this instance of trigger is triggered
     */
    public function isTriggered($params);
}
