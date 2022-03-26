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
