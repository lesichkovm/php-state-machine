<?php

class ValidatorsTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testValidators()
    {
        $sm = new ExampleStateMachineWithValidators();

        $this->assertEquals(ExampleStateMachineWithValidators::STATE_OFF, $sm->getState());

        $this->assertEquals("day", $sm->dayOrNight, "Validate current time is day");

        $this->assertFalse($sm->canTransition(ExampleStateMachineWithValidators::TRANSITION_TO_ON, "Validate cannot transition to on at day"));

        $sm->dayOrNight = "night";

        $this->assertEquals("night", $sm->dayOrNight, "Validate current time is night");

        $this->assertTrue($sm->canTransition(ExampleStateMachineWithValidators::TRANSITION_TO_ON, "Validate cand transition to on at night"));

        $sm->applyTransition(ExampleStateMachineWithValidators::TRANSITION_TO_ON);

        $this->assertEquals(ExampleStateMachineWithValidators::STATE_ON, $sm->getState());
    }
}

class ExampleStateMachineWithValidators extends StateMachine
{
    const STATE_OFF = "off";
    const STATE_ON = "on";

    const TRANSITION_TO_ON = "to_on";
    const TRANSITION_TO_OFF = "to_off";

    public $dayOrNight = "day";

    function __construct()
    {
        //parent::__construct();
        $config = [
            "states" => [
                self::STATE_OFF,
                self::STATE_ON,
            ],
            "transitions" => array(
                self::TRANSITION_TO_ON => array(
                    "from" => array(self::STATE_OFF),
                    "to" => self::STATE_ON,
                    "validators" => [
                        [$this, "validateIsNight"],
                    ]
                ),
                self::TRANSITION_TO_OFF => array(
                    "from" => array(self::STATE_ON),
                    "to" => self::STATE_OFF
                ),
            ),
        ];
        $this->setConfig($config);
    }

    function validateIsNight()
    {
        return $this->dayOrNight == "night";
    }
}
