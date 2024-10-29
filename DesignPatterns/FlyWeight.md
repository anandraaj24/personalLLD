The Flyweight Design Pattern is a structural design pattern that aims to minimize memory usage by sharing common data among similar objects. It is particularly useful when dealing with a large number of objects that share some common state or behavior. Instead of creating multiple instances of an object with identical data, the Flyweight pattern allows for the sharing of these common elements, significantly reducing memory consumption.

## Key Components of the Flyweight Pattern

1. **Flyweight**: An interface or abstract class that defines the methods for the shared objects.

2. **Concrete Flyweight**: A class that implements the Flyweight interface and holds the intrinsic state (shared data) of the object. It does not hold extrinsic state (data that can vary).

3. **Flyweight Factory**: A factory that manages the creation and management of Flyweight objects. It checks if a Flyweight already exists and returns it instead of creating a new one.

4. **Client**: The class that uses the Flyweight objects and provides the extrinsic state needed for operations.

## Benefits of the Flyweight Pattern

- **Memory Efficiency**: Reduces memory usage by sharing common data among similar objects.

- **Performance Improvement**: Enhances performance by minimizing object creation overhead.

- **Separation of Concerns**: Separates intrinsic state (shared) from extrinsic state 
(contextual), allowing for clearer code organization.

## Use Cases

- **Graphics Rendering**: In applications with a large number of similar graphical objects (e.g., trees in a game), where many instances share similar properties (e.g., color, shape).

- **Text Processing**: When rendering a large number of characters in a document, where many characters share the same font and style.

- **Database Connection Pooling**: Managing a pool of shared database connections instead of creating new ones for every request.

## Implementation in PHP

Let's illustrate the Flyweight pattern with an example of a Text Formatting system where multiple characters share the same font and style.

## Code Implementation

```php
<?php

// Flyweight Interface
interface CharacterFlyweight {
    public function display($extrinsicState);
}

// Concrete Flyweight
class ConcreteCharacter implements CharacterFlyweight {
    private $character;

    public function __construct($character) {
        $this->character = $character;
    }

    public function display($extrinsicState) {
        echo "Character: {$this->character}, Font: {$extrinsicState['font']}, Size: {$extrinsicState['size']}" . PHP_EOL;
    }
}

// Flyweight Factory
class CharacterFactory {
    private $flyweights = [];

    public function getCharacter($character) {
        if (!isset($this->flyweights[$character])) {
            $this->flyweights[$character] = new ConcreteCharacter($character);
        }
        return $this->flyweights[$character];
    }
}

// Client
class TextEditor {
    private $factory;

    public function __construct(CharacterFactory $factory) {
        $this->factory = $factory;
    }

    public function printText($text, $font, $size) {
        foreach (str_split($text) as $char) {
            $flyweight = $this->factory->getCharacter($char);
            $flyweight->display(['font' => $font, 'size' => $size]);
        }
    }
}

// Usage
$factory = new CharacterFactory();
$editor = new TextEditor($factory);

$editor->printText("hello", "Arial", 12);
$editor->printText("world", "Verdana", 14);

?>
```

## Explanation of the Code

- **Flyweight Interface (CharacterFlyweight)**: Defines the method display() for rendering characters.

- **Concrete Flyweight (ConcreteCharacter)**: Implements the Flyweight interface and contains the intrinsic state (the character itself). The display() method takes an extrinsic state (font and size) to render the character.

- **Flyweight Factory (CharacterFactory)**: Manages the creation of Flyweight objects. It checks if a character already exists in the shared pool; if not, it creates a new ConcreteCharacter.

- **Client (TextEditor)**: Uses the Flyweight objects to print text. It calls the factory to retrieve characters and uses the display() method to render each character with its respective font and size.

## Conclusion

The Flyweight Design Pattern is a powerful tool for optimizing memory usage in applications that deal with a large number of similar objects. By sharing common data, it not only reduces memory consumption but also improves performance by minimizing the overhead of object creation. This pattern is especially useful in scenarios like graphics rendering, text processing, and resource pooling, where many objects share the same intrinsic properties.

# Scenario: Gaming Application

In this example, we will manage a set of game characters that share common attributes like character type and abilities, while each instance has its own unique position and health.

## Key Components

- **Flyweight Interface**: Defines the methods for shared game characters.

- **Concrete Flyweight**: Implements the Flyweight interface and holds the intrinsic state (shared data).

- **Flyweight Factory**: Manages the creation and storage of shared Flyweight objects.

- **Client**: The game environment that uses the Flyweight objects and maintains the extrinsic state (like position and health).

## Code Implementation

```php
<?php

// Flyweight Interface
interface GameCharacter {
    public function display($extrinsicState);
}

// Concrete Flyweight
class ConcreteCharacter implements GameCharacter {
    private $characterType;
    private $abilities;

    public function __construct($characterType, $abilities) {
        $this->characterType = $characterType;
        $this->abilities = $abilities;
    }

    public function display($extrinsicState) {
        echo "Character Type: {$this->characterType}, Abilities: " . implode(", ", $this->abilities) . 
             ", Position: {$extrinsicState['position']}, Health: {$extrinsicState['health']}" . PHP_EOL;
    }
}

// Flyweight Factory
class CharacterFactory {
    private $characters = [];

    public function getCharacter($characterType, $abilities) {
        $key = $characterType . implode(",", $abilities);
        if (!isset($this->characters[$key])) {
            $this->characters[$key] = new ConcreteCharacter($characterType, $abilities);
        }
        return $this->characters[$key];
    }
}

// Client
class Game {
    private $factory;

    public function __construct(CharacterFactory $factory) {
        $this->factory = $factory;
    }

    public function createCharacter($type, $abilities, $position, $health) {
        $flyweight = $this->factory->getCharacter($type, $abilities);
        $flyweight->display(['position' => $position, 'health' => $health]);
    }
}

// Usage
$factory = new CharacterFactory();
$game = new Game($factory);

// Creating game characters
$game->createCharacter("Warrior", ["Strength", "Defense"], "X:10, Y:20", 100);
$game->createCharacter("Mage", ["Magic", "Stealth"], "X:15, Y:25", 80);
$game->createCharacter("Warrior", ["Strength", "Defense"], "X:30, Y:40", 90); // Same type and abilities as first

?>
```


# Scenario: Document Management System

In this example, we will manage a set of documents where each document can share formatting properties (like font and size), but each has its own content and metadata (like title and author).

## Key Components

- **Flyweight Interface**: Defines the methods for shared document formatting.

- **Concrete Flyweight**: Implements the Flyweight interface and holds the intrinsic state (shared formatting data).

- **Flyweight Factory**: Manages the creation and storage of shared Flyweight objects.

- **Client**: The document manager that uses the Flyweight objects and maintains extrinsic state (content and metadata).

## Code Implementation

```php
<?php

// Flyweight Interface
interface DocumentFormat {
    public function applyFormat($extrinsicState);
}

// Concrete Flyweight
class ConcreteDocumentFormat implements DocumentFormat {
    private $font;
    private $size;

    public function __construct($font, $size) {
        $this->font = $font;
        $this->size = $size;
    }

    public function applyFormat($extrinsicState) {
        echo "Document Title: {$extrinsicState['title']}, Author: {$extrinsicState['author']}, " .
             "Font: {$this->font}, Size: {$this->size}" . PHP_EOL;
    }
}

// Flyweight Factory
class FormatFactory {
    private $formats = [];

    public function getFormat($font, $size) {
        $key = $font . "_" . $size;
        if (!isset($this->formats[$key])) {
            $this->formats[$key] = new ConcreteDocumentFormat($font, $size);
        }
        return $this->formats[$key];
    }
}

// Client
class DocumentManager {
    private $factory;

    public function __construct(FormatFactory $factory) {
        $this->factory = $factory;
    }

    public function createDocument($title, $author, $font, $size) {
        $format = $this->factory->getFormat($font, $size);
        $format->applyFormat(['title' => $title, 'author' => $author]);
    }
}

// Usage
$factory = new FormatFactory();
$manager = new DocumentManager($factory);

// Creating documents with shared formats
$manager->createDocument("Document 1", "Author A", "Arial", 12);
$manager->createDocument("Document 2", "Author B", "Times New Roman", 14);
$manager->createDocument("Document 3", "Author C", "Arial", 12); // Shared format with Document 1

?>
```

# Scenario: Shape Drawing

## Key Components

- **Flyweight Interface**: An interface that defines methods that all concrete flyweights must implement.

- **Concrete Flyweight**: A class that implements the Flyweight interface and contains shared state.

- **Flyweight Factory**: A factory class that creates and manages flyweight objects. It ensures that flyweights are shared and reused.

- **Client**: The class that uses the flyweight objects, managing the unique state externally.

## Code Implementation

```php

// FlyWeight Interface.
interface Shape {
    public function draw(): void;
}

// Concrete FlyWeight
class Circle implements Shape {
    private $color; // Intrinsic state
    private $radius; // Intrinsic state

    public function __construct(string $color, float $radius) {
        $this->color = $color;
        $this->radius = $radius;
    }

    public function draw(): void {
        echo "Drawing a " . $this->color . " circle with radius " . $this->radius . "\n";
    }
}

// FlyWeight Factory
class ShapeFactory {
    private $shapes = []; // To hold shared flyweights

    public function getCircle(string $color, float $radius): Shape {
        $key = $color . "_" . $radius;

        if (!isset($this->shapes[$key])) {
            $this->shapes[$key] = new Circle($color, $radius);
        }

        return $this->shapes[$key];
    }
}

// Client
function clientCode() {
    $factory = new ShapeFactory();

    // Create and draw circles with shared state
    $circle1 = $factory->getCircle("red", 5);
    $circle2 = $factory->getCircle("blue", 5);
    $circle3 = $factory->getCircle("red", 5); // Reuses the first circle

    $circle1->draw();
    $circle2->draw();
    $circle3->draw();

    // Checking if circle1 and circle3 are the same object
    if ($circle1 === $circle3) {
        echo "circle1 and circle3 are the same instance.\n";
    } else {
        echo "circle1 and circle3 are different instances.\n";
    }
}

clientCode();
