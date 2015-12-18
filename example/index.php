<?php

require '../vendor/autoload.php';

use Aschelch\Scenario\TriggerInterface;
use Aschelch\Scenario\ActionInterface;
use Aschelch\Scenario\Scenario;
use Aschelch\Scenario\Processor;
use Aschelch\Scenario\ScenarioRepositoryInterface;

class AdminUserLoggedInTrigger implements TriggerInterface{

    public function isTriggered($params){
        return $params['group'] == 'admin';
    }

}

class ExceptionThrownTrigger implements TriggerInterface{

    private $type;

    public function __construct($type){
        $this->type = $type;
    }

    public function isTriggered($params){
        return $params['type'] == $this->type;
    }

}

class EchoUsernamedAction implements ActionInterface{

    public function execute($params){
        echo 'Hello ' . $params['username'] . '!<br/>';
        return true;
    }

}

class SendMailAction implements ActionInterface{

    private $params;

    public function __construct($params){
        $this->params = $params;
    }

    public function execute($eventParams){

        echo 'Sending mail to ' . $this->params['to']. ' : <br/>';
        echo $this->params['body'] . '<br/>';
        var_dump($eventParams);
        return true;
    }

}

class ArrayScenarioRepository implements ScenarioRepositoryInterface{

    public $scenarios = array();

    public function add($event, $scenario){
        $this->scenarios[$event][] = $scenario;
    }

    public function findAllByEvent($event){
        return $this->scenarios[$event];
    }
}

$repository = new ArrayScenarioRepository();
$repository->add('AdminUserLoggedIn', new Scenario(new AdminUserLoggedInTrigger(), new EchoUsernamedAction()));
$repository->add('AdminUserLoggedIn', new Scenario(new AdminUserLoggedInTrigger(), new SendMailAction(array('to' => 'admin@website.com', 'body' => 'An admin user logged in'))));
$repository->add('ExceptionThrown', new Scenario(new ExceptionThrownTrigger('NotFoundException'), new SendMailAction(array('to' => 'admin@website.com', 'body' => 'NotFoundException has been thrown'))));
// or
//$repository = new DatabaseScenarioRepository();

$processor = new Processor($repository);

echo "<h1>Admin user logged in</h1>";
$processor->process('AdminUserLoggedIn', array(
    'user_id'  => 1,
    'username' => 'Administrator',
    'group'    => 'admin'
));

echo "<h1>Normal user logged in</h1>";
$processor->process('AdminUserLoggedIn', array(
    'user_id'  => 2,
    'username' => 'User',
    'group'    => 'user'
));

echo "<h1>NullPointerExecption is thrown</h1>";
$processor->process('ExceptionThrown', array(
    'type'  => 'NullPointerExecption',
    'message' => 'Null pointer bla bla bla',
    'stacktrace' => array()
));

echo "<h1>NotFoundException is thrown</h1>";
$processor->process('ExceptionThrown', array(
    'type'  => 'NotFoundException',
    'message' => 'The post has not been found',
    'stacktrace' => array()
));
