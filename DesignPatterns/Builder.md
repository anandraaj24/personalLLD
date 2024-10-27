# Builder Design Pattern

The Builder Design Pattern is a creational design pattern that provides a flexible solution for constructing complex objects. It separates the construction of a complex object from its representation, allowing the same construction process to create different representations.

## Key Components

1. **Builder**: An interface that defines the steps required to create a product.
2. **ConcreteBuilder**: Implements the Builder interface and constructs the product.
3. **Product**: The complex object being built.
4. **Director**: The class that constructs the object using the Builder interface.

## Benefits

- **Separation of Concerns**: The construction logic is separate from the representation, making code easier to manage.
- **Flexibility**: Different builders can create different representations of an object using the same construction process.
- **Improved Readability**: Code is more readable, as the process of creating an object is clearly defined.

## Use Cases

- When an object needs to be created with many optional parameters.
- When the construction process involves several steps or variations.
- When you want to encapsulate the creation logic of complex objects.

## Implementation Example
```php

// Product class
class Car {
    private $engine;
    private $color;
    private $wheels;
    private $gps;

    public function __construct($engine, $color, $wheels, $gps) {
        $this->engine = $engine;
        $this->color = $color;
        $this->wheels = $wheels;
        $this->gps = $gps;
    }

    public function __toString() {
        return "Car with engine: {$this->engine}, color: {$this->color}, wheels: {$this->wheels}, GPS: {$this->gps}";
    }
}


// Builder interface
interface CarBuilder {
    public function setEngine($engine);
    public function setColor($color);
    public function setWheels($wheels);
    public function setGPS($gps);
    public function build(): Car;
}


// ConcreteBuilder class
class ConcreteCarBuilder implements CarBuilder {
    private $engine;
    private $color;
    private $wheels;
    private $gps;

    public function setEngine($engine) {
        $this->engine = $engine;
    }

    public function setColor($color) {
        $this->color = $color;
    }

    public function setWheels($wheels) {
        $this->wheels = $wheels;
    }

    public function setGPS($gps) {
        $this->gps = $gps;
    }

    public function build(): Car {
        return new Car($this->engine, $this->color, $this->wheels, $this->gps);
    }
}


// Director class
class CarDirector {
    private $builder;

    public function __construct(CarBuilder $builder) {
        $this->builder = $builder;
    }

    public function constructSportsCar() {
        $this->builder->setEngine('V8');
        $this->builder->setColor('Red');
        $this->builder->setWheels('Sport');
        $this->builder->setGPS('Advanced');
    }

    public function constructSUV() {
        $this->builder->setEngine('V6');
        $this->builder->setColor('Black');
        $this->builder->setWheels('All-Terrain');
        $this->builder->setGPS('Basic');
    }
}

// Client code
$builder = new ConcreteCarBuilder();
$director = new CarDirector($builder);

// Construct a sports car
$director->constructSportsCar();
$sportsCar = $builder->build();
echo $sportsCar . PHP_EOL;

// Construct an SUV
$builder = new ConcreteCarBuilder(); // Reset the builder for a new car
$director = new CarDirector($builder);
$director->constructSUV();
$suv = $builder->build();
echo $suv . PHP_EOL;

```

## Builder Design Pattern With Optional Features

```php

<?php

// Product
class Pizza {
    private $size;
    private $cheese;
    private $pepperoni;
    private $veggies = [];
    private $extraCheese = false;
    private $spicy = false;

    public function __construct($size, $cheese, $pepperoni, $veggies, $extraCheese, $spicy) {
        $this->size = $size;
        $this->cheese = $cheese;
        $this->pepperoni = $pepperoni;
        $this->veggies = $veggies;
        $this->extraCheese = $extraCheese;
        $this->spicy = $spicy;
    }

    public function __toString() {
        return "Pizza [Size: {$this->size}, Cheese: {$this->cheese}, Pepperoni: {$this->pepperoni}, Veggies: " . implode(", ", $this->veggies) . ", Extra Cheese: {$this->extraCheese}, Spicy: {$this->spicy}]";
    }
}

// Builder Interface
interface PizzaBuilder {
    public function setSize($size);
    public function addCheese($type);
    public function addPepperoni();
    public function addVeggies(array $veggies);
    public function addExtraCheese();
    public function addSpicy();
    public function build(): Pizza;
}

// Concrete Builder
class CustomPizzaBuilder implements PizzaBuilder {
    private $size;
    private $cheese = 'Mozzarella';
    private $pepperoni = false;
    private $veggies = [];
    private $extraCheese = false;
    private $spicy = false;

    public function setSize($size) {
        $this->size = $size;
        return $this; // Enable method chaining
    }

    public function addCheese($type) {
        $this->cheese = $type;
        return $this;
    }

    public function addPepperoni() {
        $this->pepperoni = true;
        return $this;
    }

    public function addVeggies(array $veggies) {
        $this->veggies = $veggies;
        return $this;
    }

    public function addExtraCheese() {
        $this->extraCheese = true;
        return $this;
    }

    public function addSpicy() {
        $this->spicy = true;
        return $this;
    }

    public function build(): Pizza {
        return new Pizza($this->size, $this->cheese, $this->pepperoni, $this->veggies, $this->extraCheese, $this->spicy);
    }
}

// Usage
$builder = new CustomPizzaBuilder();
$pizza = $builder->setSize('Large')
                 ->addCheese('Cheddar')
                 ->addPepperoni()
                 ->addVeggies(['Olives', 'Mushrooms'])
                 ->addExtraCheese()
                 ->addSpicy()
                 ->build();

echo $pizza;

?>
```


Yes, the Builder Design Pattern can be used in a couple of primary ways, depending on your specific needs and the complexity of the objects you're constructing. Here are the two main approaches:

## 1. Standard Builder Pattern for Complex Objects

In this approach, the Builder pattern is used to create complex objects that require a multi-step construction process. It encapsulates the construction logic and allows for creating objects with various configurations. This method is ideal for:

- **Complex Initialization**: When an object has many parameters or requires a specific sequence of steps to construct.
- **Default Values**: Allowing for defaults while providing options to override them.

**Example**: A `Pizza` object with required attributes (size, cheese type) and optional attributes (veggies, extra toppings).

## 2. Builder Pattern with Optional Features

In this variation, the Builder pattern is specifically tailored to allow users to selectively configure optional features. It provides a fluent interface that allows for:

- **Method Chaining**: Enabling multiple methods to be called in a single statement, improving readability.
- **Selective Feature Inclusion**: Users can choose to include or exclude features as needed without needing multiple constructors or complex initialization.

**Example**: The same `Pizza` builder that allows the user to add extra cheese or make it spicy if desired, but doesn’t require those features to be specified.

## Summary

- **Standard Builder Pattern**: Focuses on building complex objects with required and optional parameters. Good for managing complex construction logic.

- **Builder with Optional Features**: Enhances flexibility by allowing users to include optional features easily. It emphasizes a user-friendly interface with method chaining for better readability.

Both approaches leverage the core principles of the Builder pattern but serve different purposes depending on the complexity of the object and user requirements.
