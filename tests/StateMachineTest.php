<?php

class StateMachineTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function testStateMachine(){
        $sm = new StateMachine();
        $sm->setConfig([
            "states" => [ 
                "checkout",
                "pending",
                "confirmed",
                "cancelled"
            ],
            "transitions" => array(
                "create" => array(
                    "from" => array("checkout"),
                    "to" => "pending"
                ),
                "confirm" => array(
                    "from" => array("checkout", "pending"),
                    "to" => "confirmed"
                ),
                "cancel" => array(
                    "from" => array("confirmed"),
                    "to" => "cancelled"
                )
            ),
        ]);
        

        $this->assertEquals('checkout', $sm->getState());

        $sm->applyTransition("create");

        $this->assertEquals('pending', $sm->getState());

        $sm->applyTransition("confirm");

        $this->assertEquals('confirmed', $sm->getState());

    }

    public function testStateMachineExtended(){
        $sm = new ExampleStateMachine();

        $this->assertEquals(ExampleStateMachine::STATE_CHECKOUT, $sm->getState());

        $sm->applyTransition("create");

        $this->assertEquals(ExampleStateMachine::STATE_PENDING, $sm->getState());

        $sm->applyTransition("confirm");

        $this->assertEquals(ExampleStateMachine::STATE_CONFIRMED, $sm->getState());

        $this->assertEquals([
            ExampleStateMachine::STATE_CHECKOUT,
            ExampleStateMachine::STATE_PENDING,
            ExampleStateMachine::STATE_CONFIRMED,
        ], $sm->getHistory());

    }
}

class ExampleStateMachine extends StateMachine {
    const STATE_CHECKOUT = "checkout";
    const STATE_PENDING = "pending";
    const STATE_CONFIRMED = "confirmed";
    const STATE_CANCELLED = "cancelled";

    const TRANSITION_CREATE = "create";
    const TRANSITION_CONFIRM = "confirm";
    const TRANSITION_CANCEL = "cancel";


    function __construct() {
        //parent::__construct();
        $config = [
            "states" => [ 
                self::STATE_CHECKOUT,
                self::STATE_PENDING,
                self::STATE_CONFIRMED,
                self::STATE_PENDING
            ],
            "transitions" => array(
                self::TRANSITION_CREATE => array(
                    "from" => array(self::STATE_CHECKOUT),
                    "to" => "pending"
                ),
                self::TRANSITION_CONFIRM => array(
                    "from" => array(self::STATE_CHECKOUT, self::STATE_PENDING),
                    "to" => self::STATE_CONFIRMED
                ),
                self::TRANSITION_CANCEL => array(
                    "from" => array(self::STATE_CONFIRMED),
                    "to" => self::STATE_CANCELLED
                )
            ),
        ];
        $this->setConfig($config);
    }
}