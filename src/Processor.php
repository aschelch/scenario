<?php

namespace Aschelch\Scenario;

class Processor
{
    /**
     * @var ScenarioRepositoryInterface
     */
    private $repository;

    public function __construct(ScenarioRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function process($event, $params = array())
    {
        $executed = 0;
        $scenarios = $this->repository->findAllByEvent($event);
        foreach ($scenarios as $scenario) {
            if (! $scenario->isTriggered($params)) {
                continue;
            }

            if ($scenario->execute($params)) {
                $executed++;
            }
        }

        return $executed;
    }
}
