# Template Method Design Pattern

The Template Method Design Pattern is a behavioral design pattern that defines the skeleton of an algorithm in a method, deferring some steps to subclasses. It lets subclasses redefine certain steps of an algorithm without changing its structure. This pattern promotes code reuse and enforces a consistent approach to algorithms.

## Key Components of the Template Method Pattern

1. **Abstract Class**: Contains the template method and defines the steps of the algorithm. It may also define default implementations for some steps.
2. **Concrete Class**: Subclasses that implement or override specific steps of the algorithm defined in the abstract class.

## Benefits of the Template Method Pattern

- **Code Reusability**: Common parts of an algorithm are implemented in the abstract class, avoiding duplication in subclasses.
- **Control Over Inheritance**: The template method provides a controlled way to extend or change parts of the algorithm in subclasses.
- **Consistency**: Enforces a consistent structure for algorithms, ensuring that certain steps are always executed in the correct order.

## Use Cases

- Frameworks where the base class defines the flow of a process, while subclasses provide specific implementations.
- Handling operations that follow a common algorithmic structure, such as data processing, where different types of data require different processing methods.
- Games where different characters may share the same core behavior but differ in certain aspects (e.g., movement or attack methods).

## Implementation in PHP

Here’s a simple implementation of the Template Method Design Pattern in PHP:

```php
<?php

// Abstract Class
abstract class DataProcessor {
    // Template Method
    public function process() {
        $this->readData();
        $this->processData();
        $this->saveData();
    }

    // Steps of the algorithm
    abstract protected function readData();
    abstract protected function processData();
    abstract protected function saveData();
}

// Concrete Class for CSV Data Processing
class CsvDataProcessor extends DataProcessor {
    protected function readData() {
        echo "Reading data from CSV file.\n";
    }

    protected function processData() {
        echo "Processing CSV data.\n";
    }

    protected function saveData() {
        echo "Saving data to database from CSV.\n";
    }
}

// Concrete Class for JSON Data Processing
class JsonDataProcessor extends DataProcessor {
    protected function readData() {
        echo "Reading data from JSON file.\n";
    }

    protected function processData() {
        echo "Processing JSON data.\n";
    }

    protected function saveData() {
        echo "Saving data to database from JSON.\n";
    }
}

// Client code
$csvProcessor = new CsvDataProcessor();
$csvProcessor->process();
// Output:
// Reading data from CSV file.
// Processing CSV data.
// Saving data to database from CSV.

$jsonProcessor = new JsonDataProcessor();
$jsonProcessor->process();
// Output:
// Reading data from JSON file.
// Processing JSON data.
// Saving data to database from JSON.
```

## Scenario: Document Processing

```php

<?php

// Abstract Class
abstract class Document {
    // Template Method
    public final function processDocument() {
        $this->openDocument();
        $this->readContent();
        $this->saveDocument();
        $this->closeDocument();
    }

    // Common steps
    protected function openDocument() {
        echo "Opening document\n";
    }

    protected function closeDocument() {
        echo "Closing document\n";
    }

    // Abstract methods for subclasses to implement
    abstract protected function readContent();
    abstract protected function saveDocument();
}

// Concrete Class for PDF Document
class PdfDocument extends Document {
    protected function readContent() {
        echo "Reading content from PDF document\n";
    }

    protected function saveDocument() {
        echo "Saving changes to PDF document\n";
    }
}

// Concrete Class for Word Document
class WordDocument extends Document {
    protected function readContent() {
        echo "Reading content from Word document\n";
    }

    protected function saveDocument() {
        echo "Saving changes to Word document\n";
    }
}

// Client code
$pdfDoc = new PdfDocument();
$pdfDoc->processDocument();
// Output:
// Opening document
// Reading content from PDF document
// Saving changes to PDF document
// Closing document

$wordDoc = new WordDocument();
$wordDoc->processDocument();
// Output:
// Opening document
// Reading content from Word document
// Saving changes to Word document
// Closing document
```

## Scenario: Meal Preparation

```php
<?php

// Abstract Class
abstract class Meal {
    // Template Method
    public final function prepareMeal() {
        $this->prepareIngredients();
        $this->cook();
        $this->serve();
    }

    // Common steps
    protected function prepareIngredients() {
        echo "Preparing ingredients\n";
    }

    protected function serve() {
        echo "Serving the meal\n";
    }

    // Abstract methods for subclasses to implement
    abstract protected function cook();
}

// Concrete Class for Soup
class Soup extends Meal {
    protected function cook() {
        echo "Cooking the soup\n";
    }
}

// Concrete Class for Salad
class Salad extends Meal {
    protected function cook() {
        echo "Mixing the salad ingredients\n";
    }
}

// Client code
$soup = new Soup();
$soup->prepareMeal();
// Output:
// Preparing ingredients
// Cooking the soup
// Serving the meal

$salad = new Salad();
$salad->prepareMeal();
// Output:
// Preparing ingredients
// Mixing the salad ingredients
// Serving the meal
```