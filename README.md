# PHP State Machine (PSM)

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
```

## Persisting State

The PSM makes it easy to persist the state to a file or database, and restore later.

### Saving PSM to File

```php
$sm = new StateMachine();
file_put_contents('sm.json', $sm->toString());
```

### Restoring PSM from File

```php
$sm = new StateMachine();
$sm->fromString(json_decode(file_get_contents('sm.json'), true));
```
