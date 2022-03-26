# PHP State Machine (PSM)


[![Tests Status](https://github.com/lesichkovm/php-state-machine/actions/workflows/php.yml/badge.svg?branch=master)](https://github.com/lesichkovm/php-state-machine/actions/workflows/php.yml)

The PHP State Machine (PSM) is a state machine that is easy to understand and implement.

The PSM consists of a single file that is easy to drag and drop into your project.

## Configuration

```php
$config = [
    'states' => array(
        'checkout',
        'pending',
        'confirmed',
        'cancelled'
    ),
    'transitions' => array(
        'create' => array(
            'from' => array('checkout'),
            'to' => 'pending'
        ),
        'confirm' => array(
            'from' => array('checkout', 'pending'),
            'to' => 'confirmed'
        ),
        'cancel' => array(
            'from' => array('confirmed'),
            'to' => 'cancelled'
        )
    ),
];
$stateMachine = new \App\Helpers\StateMachine;
$stateMachine->setConfig($config);
```

## Get Current State

PSM will give you the current state

```php
var_dump($stateMachine->getState());
```

## Check if Transition can be Applied

Before applying a transition, check whether it can be applied

```php
// Return true, we can apply this transition
var_dump($stateMachine->canTransition('create'));
var_dump($stateMachine->applyTransition('create'));
```

## Get Possible Transitions

PSM can easily show the possible transitions from the current state

```php
// All possible transitions for pending state are just "confirm"
var_dump($stateMachine->getPossibleTransitions());
```

## Get History

PSM keeps track of the history.

```php
var_dump($stateMachine->getHistory());
```

## Persisting State

The PSM makes it easy to persist the state to a file or database, and restore later.

### Saving PSM to File

```php
$stateMachine = new StateMachine();
file_put_contents('sm.json', $stateMachine->toString());
```

### Restoring PSM from File

```php
$stateMachine = new StateMachine();
$stateMachine->fromString(json_decode(file_get_contents('sm.json'), true));
```



## Examples

### 1. Smart Lamp

This is an example of a lamp that can be switched on and off. 
It is smart as can only only switch on during the night to save energy.
The check if it can be turned on is done via a validator


```php
class SmartLamp extends StateMachine
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
```

How to use:

```php
$lamp = new SmartLamp;

$lamp->dayOrNight = "day"; // We tell the lamp its day

// This will not execute, as its currently day time
if ($lamp->canTransition(SmartLamp::TRANSITION_TO_ON)) {
    $lamp->applyTransition(SmartLamp::TRANSITION_TO_ON);
}

$lamp->dayOrNight = "night"; // Now we tell the lamp its night

// This will execute, as its currently night time
if ($lamp->canTransition(SmartLamp::TRANSITION_TO_ON)) {
    $lamp->applyTransition(SmartLamp::TRANSITION_TO_ON);
}
```