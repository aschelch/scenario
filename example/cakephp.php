<?php

require '../vendor/autoload.php';

use Aschelch\Scenario\ScenarioRepositoryInterface;
use Aschelch\Scenario\Processor;

class ScenarioRepository implements ScenarioRepositoryInterface{

    public function findAllByEvent($event){
        $results = array();

        $scenarios = ClassRegistry::init('Scenario.Scenario')->findAllByEvent($event);

        foreach ($scenarios as $scenario) {

            list($plugin, $triggerClass) = pluginSplit($scenario['ScenarioScenario']['trigger']);
            $triggerClass .= 'Trigger';
            $triggerParams = json_decode($scenario['ScenarioScenario']['trigger_parameters'], true);

            App::uses($triggerClass, $plugin . '.Scenario/Trigger');
            $trigger = new $triggerClass();


            list($plugin, $actionClass) = pluginSplit($scenario['ScenarioScenario']['action']);
            $actionClass .= 'Action';
            $actionParams = json_decode($scenario['ScenarioScenario']['action_parameters'], true);

            App::uses($actionClass, $plugin . '.Scenario/Action');
            $action = new $actionClass();

            $results[] = new Scenario($trigger, $action);
        }


        return $results;
    }
}



