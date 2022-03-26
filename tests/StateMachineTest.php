<?php

class StateMachineTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function testTrue(){
        //$flow = new StateMachine();

        $this->assertTrue(true);

    }
}

// class ExampleWorkflow extends StateMachine {
//     function __construct() {
//         parent::__construct();
//     }
// }