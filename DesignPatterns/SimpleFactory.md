# Simple Factory Design Pattern

The Simple Factory Design Pattern is a creational design pattern that provides a way to create objects without specifying the exact class of the object that will be created. This pattern encapsulates the object creation process in a single method, simplifying the instantiation of classes.

## Key Components of the Simple Factory Pattern

1. **Product**: An interface or abstract class that defines the common interface for the objects created by the factory.
2. **Concrete Products**: Classes that implement the product interface. These are the actual objects created by the factory.
3. **Factory**: A class that contains a static method for creating objects. It decides which class to instantiate based on the input parameters.

## Benefits of the Simple Factory Pattern

- **Encapsulation**: The object creation logic is centralized, making it easier to manage and modify.
- **Flexibility**: New product types can be added without modifying existing code, adhering to the Open/Closed Principle.
- **Reduced Coupling**: The client code does not need to know about the concrete classes; it only interacts with the factory.

## Use Cases

- When you have a common interface or abstract class and multiple implementations.
- When object creation logic needs to be centralized for maintainability.
- When you want to manage and hide the creation details from the client code.

## PHP Implementation of the Simple Factory Pattern

Here's a simple example of the Simple Factory Pattern in PHP, where we create different types of vehicles.

```php
<?php

// Product Interface
interface Vehicle {
    public function drive();
}

// Concrete Product 1
class Car implements Vehicle {
    public function drive() {
        return "Driving a car.";
    }
}

// Concrete Product 2
class Truck implements Vehicle {
    public function drive() {
        return "Driving a truck.";
    }
}

// Concrete Product 3
class Motorcycle implements Vehicle {
    public function drive() {
        return "Riding a motorcycle.";
    }
}

// Enum for Vehicle Types
class VehicleType {
    const CAR = 'car';
    const TRUCK = 'truck';
    const MOTORCYCLE = 'motorcycle';
}

// Factory Class
class VehicleFactory {
    public static function createVehicle($type) {
        switch ($type) {
            case VehicleType::CAR:
                return new Car();
            case VehicleType::TRUCK:
                return new Truck();
            case VehicleType::MOTORCYCLE:
                return new Motorcycle();
            default:
                throw new InvalidArgumentException("Unknown vehicle type: $type");
        }
    }
}

// Client Code
try {
    $car = VehicleFactory::createVehicle('car');
    echo $car->drive() . "\n";

    $truck = VehicleFactory::createVehicle('truck');
    echo $truck->drive() . "\n";

    $motorcycle = VehicleFactory::createVehicle('motorcycle');
    echo $motorcycle->drive() . "\n";

    // This will throw an exception
    $bicycle = VehicleFactory::createVehicle('bicycle');
    echo $bicycle->drive() . "\n";

} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>
