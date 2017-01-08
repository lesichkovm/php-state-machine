# PHP State Machine (PSM)

The PHP State Machine (PSM) is a state machine that is easy to understand and implement.

The PSM consists of a single file that is easy to drag and drop into your project.

## Persisting State

The PSM makes it easy to persist the state to a file or database, and restore later.

### Saving PSM to File

$sm = new StateMachine();
file_put_contents('sm.json', $sm->toString());


### Restoring PSM from File

$sm = new StateMachine();
$sm->fromString(json_decode(file_get_contents('sm.json'), true));
