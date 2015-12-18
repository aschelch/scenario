<?php

namespace Aschelch\Scenario;

interface ActionInterface
{

    /**
     * Execute the action using event's parameters
     *
     * @param  array Event params
     * @return boolean true if the action has been executed, false otherwise
     */
    public function execute($params);
}
