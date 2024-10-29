# Prototype Design Patterns

The Prototype Design Pattern is a creational design pattern that allows you to create new objects by copying an existing object, known as the prototype. This pattern is particularly useful when the cost of creating a new instance of an object is more expensive than copying an existing one.

## Key Components of the Prototype Pattern

1. **Prototype Interface**: An interface that declares a method for cloning itself.
2. **Concrete Prototype**: A class that implements the Prototype interface and provides the cloning logic.
3. **Client**: The code that uses the prototype to create new objects.
Benefits of the Prototype Pattern
4. **Performance**: Cloning an object can be faster than creating a new instance from scratch, especially for complex objects.
5. **Simplicity**: The pattern simplifies the object creation process by providing a way to duplicate existing objects.
6. **Flexibility**: New types of objects can be added without changing existing code, adhering to the Open/Closed Principle.

## Use Cases

- When object creation is costly, and you want to avoid the overhead of initializing a new instance.
- When the system needs to create many identical objects that differ only in certain properties.
- When you want to avoid the complexity of subclassing for every object type.
PHP Implementation of the Prototype Pattern


```php

<?php

// Prototype Interface
interface Shape {
    public function draw(): string;
}

// Concrete Prototype 1
class Circle implements Shape {
    private $radius;

    public function __construct($radius) {
        $this->radius = $radius;
    }

    public function draw(): string {
        return "Drawing a circle with radius " . $this->radius;
    }

    public function setRadius(Integer $radius): void{
        $this->radius = $radius;
    }
}

// Concrete Prototype 2
class Square implements Shape {
    private $sideLength;

    public function __construct($sideLength) {
        $this->sideLength = $sideLength;
    }

    public function draw(): string {
        return "Drawing a square with side length " . $this->sideLength;
    }

    public function setSideLength(Integer $sideLength): void{
        $this->sideLength = $sideLength;
    }
}

// Client Code
function clientCode() {
    // Create an original shape
    $circle = new Circle(5);
    $square = new Square(10);

    // Clone the shapes
    $circleClone = clone $circle;
    $circleClone->setRadius(8);
    $squareClone = clone $square;
    $squareClone->setSideLength(15);

    // Draw the original and cloned shapes
    echo $circle->draw() . "\n";         // Drawing a circle with radius 5
    echo $circleClone->draw() . "\n";    // Drawing a circle with radius 8
    echo $square->draw() . "\n";         // Drawing a square with side length 10
    echo $squareClone->draw() . "\n";    // Drawing a square with side length 15
}

clientCode();

?>
```

## Explanation of the Code

1. **Prototype Interface (`Shape`)**: This interface declares the `clone` method for duplicating objects and a `draw` method for visual representation.

2. **Concrete Prototypes**:

- `Circle` and `Square` classes implement the `Shape` interface.

- Each class provides its own implementation of the `clone` method, returning a new instance of itself with the same properties.

3. **Client Code**:

- The `clientCode` function creates original instances of `Circle` and `Square`.
- It then clones these shapes and demonstrates that the cloned objects can be used independently, while still retaining the properties of the original objects.

## Conclusion

The Prototype Design Pattern is a powerful and efficient way to create new objects by copying existing ones. It reduces the overhead of object creation, enhances performance, and simplifies code by allowing the creation of new instances without resorting to complex initialization logic. This pattern is particularly useful in scenarios where object creation is expensive or when you need to create many instances of similar objects.