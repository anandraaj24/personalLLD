# Decorator Design Pattern

The Decorator Design Pattern is a structural design pattern that allows behavior to be added to individual objects dynamically without affecting the behavior of other objects from the same class. This pattern is useful for adhering to the Single Responsibility Principle by allowing functionality to be divided between classes with unique areas of concern.

## Key Components of the Decorator Pattern

1. **Component**: An interface or abstract class that defines the common interface for both the concrete components and decorators.
  
2. **Concrete Component**: A class that implements the component interface. This is the object to which additional responsibilities can be added.

3. **Decorator**: An abstract class that implements the component interface and contains a reference to a component object. It delegates calls to the component, and subclasses can override these methods to add additional behavior.

4. **Concrete Decorators**: These are classes that extend the decorator class and add responsibilities or behavior.

## Benefits of the Decorator Pattern

- **Flexibility**: New responsibilities can be added dynamically without altering existing code.
  
- **Single Responsibility Principle**: Each class is focused on one responsibility, making the system easier to understand and maintain.

- **Composability**: Multiple decorators can be combined to create complex behavior from simple components.

## Use Cases

- **UI Components**: Adding features like borders, scrollbars, or shadows to UI elements without modifying their core functionality.
  
- **Stream Processing**: Enhancing data streams with additional functionality (like compression or encryption) dynamically.

- **Adding Responsibilities**: Any scenario where additional responsibilities need to be added to an object dynamically.

## Implementation in PHP

Let’s illustrate the Decorator pattern with an example of a `Coffee` class, where we want to add different condiments (like milk, sugar) dynamically.

### Code Implementation

```php
<?php

// Component Interface
interface Coffee {
    public function cost(): float;
    public function description(): string;
}

// Concrete Component
class BasicCoffee implements Coffee {
    public function cost(): float {
        return 5.00; // base cost
    }

    public function description(): string {
        return "Basic Coffee";
    }
}

// Decorator
abstract class CoffeeDecorator implements Coffee {
    protected $coffee;

    public function __construct(Coffee $coffee) {
        $this->coffee = $coffee;
    }

    abstract public function cost(): float;
    abstract public function description(): string;
}

// Concrete Decorator: Milk
class MilkDecorator extends CoffeeDecorator {
    public function cost(): float {
        return $this->coffee->cost() + 1.50; // adding milk cost
    }

    public function description(): string {
        return $this->coffee->description() . ", Milk";
    }
}

// Concrete Decorator: Sugar
class SugarDecorator extends CoffeeDecorator {
    public function cost(): float {
        return $this->coffee->cost() + 0.50; // adding sugar cost
    }

    public function description(): string {
        return $this->coffee->description() . ", Sugar";
    }
}

// Usage
$basicCoffee = new BasicCoffee();
echo $basicCoffee->description() . " costs: $" . $basicCoffee->cost() . PHP_EOL;

// Adding milk to the coffee
$milkCoffee = new MilkDecorator($basicCoffee);
echo $milkCoffee->description() . " costs: $" . $milkCoffee->cost() . PHP_EOL;

// Adding sugar to the milk coffee
$sugarMilkCoffee = new SugarDecorator($milkCoffee);
echo $sugarMilkCoffee->description() . " costs: $" . $sugarMilkCoffee->cost() . PHP_EOL;

?>
```

# Decorator Design Pattern Example: Text Formatting

## Scenario: Text Formatting

In this scenario, we want to create a simple text system that allows us to apply various styles (like bold, italic, and underline) to text. We can start with a basic text component and use decorators to add different styles.

### Key Components

1. **Component**: An interface for text that defines methods for getting the text content.
  
2. **Concrete Component**: A class that implements the component interface, representing the basic text.

3. **Decorator**: An abstract class that implements the component interface and has a reference to a text object.

4. **Concrete Decorators**: Classes that extend the decorator class and add specific formatting behavior.

### Code Implementation

Here's how you can implement this scenario in PHP:

```php
<?php

// Component Interface
interface Text {
    public function getContent(): string;
}

// Concrete Component
class SimpleText implements Text {
    private $content;

    public function __construct(string $content) {
        $this->content = $content;
    }

    public function getContent(): string {
        return $this->content;
    }
}

// Decorator
abstract class TextDecorator implements Text {
    protected $text;

    public function __construct(Text $text) {
        $this->text = $text;
    }

    abstract public function getContent(): string;
}

// Concrete Decorator: Bold
class BoldDecorator extends TextDecorator {
    public function getContent(): string {
        return "<b>" . $this->text->getContent() . "</b>"; // Adding bold tags
    }
}

// Concrete Decorator: Italic
class ItalicDecorator extends TextDecorator {
    public function getContent(): string {
        return "<i>" . $this->text->getContent() . "</i>"; // Adding italic tags
    }
}

// Concrete Decorator: Underline
class UnderlineDecorator extends TextDecorator {
    public function getContent(): string {
        return "<u>" . $this->text->getContent() . "</u>"; // Adding underline tags
    }
}

// Usage
$text = new SimpleText("Hello, World!");
echo $text->getContent() . PHP_EOL; // Output: Hello, World!

// Adding bold formatting
$boldText = new BoldDecorator($text);
echo $boldText->getContent() . PHP_EOL; // Output: <b>Hello, World!</b>

// Adding italic formatting
$italicText = new ItalicDecorator($boldText);
echo $italicText->getContent() . PHP_EOL; // Output: <i><b>Hello, World!</b></i>

// Adding underline formatting
$underlinedText = new UnderlineDecorator($italicText);
echo $underlinedText->getContent() . PHP_EOL; // Output: <u><i><b>Hello, World!</b></i></u>

?>

This pattern is used to show the basic things with a special feature which is optional.