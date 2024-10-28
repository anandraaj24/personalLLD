# Command Design Pattern

The Command design pattern is a behavioral design pattern that encapsulates a request as an object, thereby allowing for parameterization of clients with queues, requests, and operations. This pattern provides a way to decouple the sender of a request from its receiver, making the system more flexible and extensible.

## Key Components of the Command Pattern

1. **Command Interface**: An interface that declares a method for executing a command.
2. **Concrete Command**: Classes that implement the command interface, defining the binding between a receiver and an action.
3. **Receiver**: The class that knows how to perform the operations associated with the command.
4. **Invoker**: The class that holds commands and invokes them, allowing for actions to be performed without needing to know the details of the receiver.
5. **Client**: The class that creates and configures command objects.

## Benefits of the Command Pattern

- **Decoupling**: The sender of the request is decoupled from the receiver, allowing for more flexible and maintainable code.
- **Undo/Redo functionality**: Commands can be stored in a queue, allowing for undo and redo operations.
- **Logging**: Commands can be logged for tracking or auditing purposes.
- **Support for Queued Requests**: Commands can be queued and executed at a later time.

## Use Cases

- Implementing actions in graphical user interfaces (e.g., menu actions, buttons).
- Command-based interfaces, such as REST APIs.
- Batch processing and queuing tasks.
- Implementing undo/redo functionality in applications.

## Implementation in PHP

Here’s a detailed implementation of the Command pattern using a simple example of a **light control system**.

### Implementation Code

```php
// Command Interface
interface Command {
    public function execute();
    public function undo();
}

// Receiver
class Light {
    public function on() {
        echo "The light is ON.\n";
    }

    public function off() {
        echo "The light is OFF.\n";
    }
}

// Concrete Command for turning the light on
class LightOnCommand implements Command {
    private $light;

    public function __construct(Light $light) {
        $this->light = $light;
    }

    public function execute() {
        $this->light->on();
    }

    public function undo() {
        $this->light->off();
    }
}

// Concrete Command for turning the light off
class LightOffCommand implements Command {
    private $light;

    public function __construct(Light $light) {
        $this->light = $light;
    }

    public function execute() {
        $this->light->off();
    }

    public function undo() {
        $this->light->on();
    }
}

// Invoker
class RemoteControl {
    private $command;

    public function setCommand(Command $command) {
        $this->command = $command;
    }

    public function pressButton() {
        if ($this->command) {
            $this->command->execute();
        }
    }

    public function pressUndo() {
        if ($this->command) {
            $this->command->undo();
        }
    }
}

// Usage
$light = new Light();
$lightOn = new LightOnCommand($light);
$lightOff = new LightOffCommand($light);

$remote = new RemoteControl();

// Turn the light on
$remote->setCommand($lightOn);
$remote->pressButton(); // The light is ON.

// Turn the light off
$remote->setCommand($lightOff);
$remote->pressButton(); // The light is OFF.

// Undo the last command (turn the light back on)
$remote->pressUndo(); // The light is ON.
```

## Example: Text Editor

```php

// Command Interface
interface Command {
    public function execute();
    public function undo();
}

// Receiver
class TextEditor {
    private $text = '';

    public function type($input) {
        $this->text .= $input;
        echo "Current Text: " . $this->text . "\n";
    }

    public function delete($length) {
        $this->text = substr($this->text, 0, -$length);
        echo "Current Text: " . $this->text . "\n";
    }

    public function getText() {
        return $this->text;
    }
}

// Concrete Command for typing
class TypeCommand implements Command {
    private $editor;
    private $text;

    public function __construct(TextEditor $editor, $text) {
        $this->editor = $editor;
        $this->text = $text;
    }

    public function execute() {
        $this->editor->type($this->text);
    }

    public function undo() {
        $this->editor->delete(strlen($this->text));
    }
}

// Invoker
class CommandHistory {
    private $history = [];
    private $redoStack = [];

    public function executeCommand(Command $command) {
        $command->execute();
        $this->history[] = $command;
        // Clear the redo stack after a new command
        $this->redoStack = [];
    }

    public function undo() {
        if (!empty($this->history)) {
            $command = array_pop($this->history);
            $command->undo();
            $this->redoStack[] = $command; // Push to redo stack
        } else {
            echo "No commands to undo.\n";
        }
    }

    public function redo() {
        if (!empty($this->redoStack)) {
            $command = array_pop($this->redoStack);
            $command->execute();
            $this->history[] = $command; // Push back to history
        } else {
            echo "No commands to redo.\n";
        }
    }
}

// Usage
$editor = new TextEditor();
$history = new CommandHistory();

// Typing commands
$history->executeCommand(new TypeCommand($editor, "Hello"));
$history->executeCommand(new TypeCommand($editor, " World"));

// Undo last command (delete " World")
$history->undo(); // Current Text: Hello

// Redo last command (add " World" back)
$history->redo(); // Current Text: Hello World

// Undo again (delete " World")
$history->undo(); // Current Text: Hello
```

## Example: Home Automation System

```php

// Command Interface
interface Command {
    public function execute();
    public function undo();
}

// Receiver classes
class Light {
    public function on() {
        echo "The light is ON.\n";
    }

    public function off() {
        echo "The light is OFF.\n";
    }
}

class Fan {
    public function on() {
        echo "The fan is ON.\n";
    }

    public function off() {
        echo "The fan is OFF.\n";
    }
}

class Door {
    public function lock() {
        echo "The door is LOCKED.\n";
    }

    public function unlock() {
        echo "The door is UNLOCKED.\n";
    }
}

// Concrete Commands
class LightOnCommand implements Command {
    private $light;

    public function __construct(Light $light) {
        $this->light = $light;
    }

    public function execute() {
        $this->light->on();
    }

    public function undo() {
        $this->light->off();
    }
}

class LightOffCommand implements Command {
    private $light;

    public function __construct(Light $light) {
        $this->light = $light;
    }

    public function execute() {
        $this->light->off();
    }

    public function undo() {
        $this->light->on();
    }
}

class FanOnCommand implements Command {
    private $fan;

    public function __construct(Fan $fan) {
        $this->fan = $fan;
    }

    public function execute() {
        $this->fan->on();
    }

    public function undo() {
        $this->fan->off();
    }
}

class FanOffCommand implements Command {
    private $fan;

    public function __construct(Fan $fan) {
        $this->fan = $fan;
    }

    public function execute() {
        $this->fan->off();
    }

    public function undo() {
        $this->fan->on();
    }
}

class LockCommand implements Command {
    private $door;

    public function __construct(Door $door) {
        $this->door = $door;
    }

    public function execute() {
        $this->door->lock();
    }

    public function undo() {
        $this->door->unlock();
    }
}

class UnlockCommand implements Command {
    private $door;

    public function __construct(Door $door) {
        $this->door = $door;
    }

    public function execute() {
        $this->door->unlock();
    }

    public function undo() {
        $this->door->lock();
    }
}

// Invoker
class RemoteControl {
    private $command;

    public function setCommand(Command $command) {
        $this->command = $command;
    }

    public function pressButton() {
        if ($this->command) {
            $this->command->execute();
        }
    }

    public function pressUndo() {
        if ($this->command) {
            $this->command->undo();
        }
    }
}

// Usage
$light = new Light();
$fan = new Fan();
$door = new Door();

$remote = new RemoteControl();

// Control the light
$lightOn = new LightOnCommand($light);
$lightOff = new LightOffCommand($light);

$remote->setCommand($lightOn);
$remote->pressButton(); // The light is ON.
$remote->pressUndo();   // The light is OFF.

$remote->setCommand($lightOff);
$remote->pressButton(); // The light is OFF.
$remote->pressUndo();   // The light is ON.

// Control the fan
$fanOn = new FanOnCommand($fan);
$fanOff = new FanOffCommand($fan);

$remote->setCommand($fanOn);
$remote->pressButton(); // The fan is ON.
$remote->pressUndo();   // The fan is OFF.

$remote->setCommand($fanOff);
$remote->pressButton(); // The fan is OFF.
$remote->pressUndo();   // The fan is ON.

// Control the door
$lockCommand = new LockCommand($door);
$unlockCommand = new UnlockCommand($door);

$remote->setCommand($lockCommand);
$remote->pressButton(); // The door is LOCKED.
$remote->pressUndo();   // The door is UNLOCKED.

$remote->setCommand($unlockCommand);
$remote->pressButton(); // The door is UNLOCKED.
$remote->pressUndo();   // The door is LOCKED.
