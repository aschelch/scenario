<?php

namespace Aschelch\Scenario;

interface ScenarioRepositoryInterface
{

    /**
     * Find all scenarios for an event type
     *
     * @param string Type of event
     * @return array List of scenarios
     */
    public function findAllByEvent($event);
}
