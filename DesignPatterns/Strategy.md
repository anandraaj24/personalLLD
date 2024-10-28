# Strategy Design Pattern

The Strategy design pattern is a behavioral design pattern that enables selecting an algorithm's behavior at runtime. It defines a family of algorithms, encapsulates each one, and makes them interchangeable. This pattern lets the algorithm vary independently from the clients that use it, promoting the Open/Closed Principle.

## Key Components of the Strategy Pattern

1. **Strategy Interface**: Defines a common interface for all supported algorithms.
2. **Concrete Strategies**: Implementations of the Strategy interface that define specific algorithms.
3. **Context**: Maintains a reference to a Strategy object and delegates the algorithm execution to the current Strategy.

## Benefits of the Strategy Pattern

- **Flexibility**: Algorithms can be selected at runtime without changing the client code.
- **Decoupling**: Clients are decoupled from the implementation of algorithms, promoting cleaner code.
- **Maintainability**: New strategies can be added with minimal changes to existing code.

## Use Cases

- Sorting algorithms where the sorting method can change based on user preference.
- Payment processing in e-commerce applications (e.g., credit card, PayPal).
- Navigation systems with different route calculation strategies.

## Implementation in PHP

Here’s a detailed implementation of the Strategy pattern in PHP, using a simple example of a payment processing system.

```php
// Strategy Interface
interface PaymentStrategy {
    public function pay($amount);
}

// Concrete Strategies
class CreditCardPayment implements PaymentStrategy {
    private $cardNumber;

    public function __construct($cardNumber) {
        $this->cardNumber = $cardNumber;
    }

    public function pay($amount) {
        echo "Paid $amount using Credit Card: $this->cardNumber\n";
    }
}

class PayPalPayment implements PaymentStrategy {
    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function pay($amount) {
        echo "Paid $amount using PayPal: $this->email\n";
    }
}

class BitcoinPayment implements PaymentStrategy {
    private $bitcoinAddress;

    public function __construct($bitcoinAddress) {
        $this->bitcoinAddress = $bitcoinAddress;
    }

    public function pay($amount) {
        echo "Paid $amount using Bitcoin: $this->bitcoinAddress\n";
    }
}

// Context
class ShoppingCart {
    private $paymentStrategy;

    public function setPaymentStrategy(PaymentStrategy $strategy) {
        $this->paymentStrategy = $strategy;
    }

    public function checkout($amount) {
        if (!$this->paymentStrategy) {
            throw new Exception("Payment strategy not set.");
        }
        $this->paymentStrategy->pay($amount);
    }
}

// Usage
$cart = new ShoppingCart();

// Pay using Credit Card
$cart->setPaymentStrategy(new CreditCardPayment("1234-5678-9876-5432"));
$cart->checkout(100);

// Pay using PayPal
$cart->setPaymentStrategy(new PayPalPayment("user@example.com"));
$cart->checkout(150);

// Pay using Bitcoin
$cart->setPaymentStrategy(new BitcoinPayment("1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa"));
$cart->checkout(200);
```

## Example: Sorting Context

```php

// Strategy Interface
interface SortingStrategy {
    public function sort(array $data): array;
}

// Concrete Strategies
class BubbleSort implements SortingStrategy {
    public function sort(array $data): array {
        $n = count($data);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($data[$j] > $data[$j + 1]) {
                    // Swap
                    $temp = $data[$j];
                    $data[$j] = $data[$j + 1];
                    $data[$j + 1] = $temp;
                }
            }
        }
        return $data;
    }
}

class QuickSort implements SortingStrategy {
    public function sort(array $data): array {
        if (count($data) < 2) {
            return $data;
        }
        $pivot = $data[0];
        $left = array_filter(array_slice($data, 1), fn($x) => $x <= $pivot);
        $right = array_filter(array_slice($data, 1), fn($x) => $x > $pivot);
        return array_merge((new QuickSort())->sort($left), [$pivot], (new QuickSort())->sort($right));
    }
}

class MergeSort implements SortingStrategy {
    public function sort(array $data): array {
        if (count($data) < 2) {
            return $data;
        }
        $mid = floor(count($data) / 2);
        return $this->merge(
            (new MergeSort())->sort(array_slice($data, 0, $mid)),
            (new MergeSort())->sort(array_slice($data, $mid))
        );
    }

    private function merge(array $left, array $right): array {
        $result = [];
        while (count($left) && count($right)) {
            if ($left[0] <= $right[0]) {
                $result[] = array_shift($left);
            } else {
                $result[] = array_shift($right);
            }
        }
        return array_merge($result, $left, $right);
    }
}

// Context
class SortContext {
    private $strategy;

    public function setSortingStrategy(SortingStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function sort(array $data): array {
        if (!$this->strategy) {
            throw new Exception("Sorting strategy not set.");
        }
        return $this->strategy->sort($data);
    }
}

// Usage
$data = [34, 7, 23, 32, 5, 62];

$sortContext = new SortContext();

// Using Bubble Sort
$sortContext->setSortingStrategy(new BubbleSort());
echo "Bubble Sort: " . implode(", ", $sortContext->sort($data)) . "\n";

// Using Quick Sort
$sortContext->setSortingStrategy(new QuickSort());
echo "Quick Sort: " . implode(", ", $sortContext->sort($data)) . "\n";

// Using Merge Sort
$sortContext->setSortingStrategy(new MergeSort());
echo "Merge Sort: " . implode(", ", $sortContext->sort($data)) . "\n";
```

## Example: Tax calculation system

```php

// Strategy Interface
interface TaxStrategy {
    public function calculateTax($amount): float;
}

// Concrete Strategies
class USATax implements TaxStrategy {
    public function calculateTax($amount): float {
        return $amount * 0.07; // 7% sales tax
    }
}

class UKTax implements TaxStrategy {
    public function calculateTax($amount): float {
        return $amount * 0.20; // 20% VAT
    }
}

class NoTax implements TaxStrategy {
    public function calculateTax($amount): float {
        return 0; // No tax
    }
}

// Context
class ShoppingCart {
    private $items = [];
    private $taxStrategy;

    public function addItem($item, $price) {
        $this->items[$item] = $price;
    }

    public function setTaxStrategy(TaxStrategy $strategy) {
        $this->taxStrategy = $strategy;
    }

    public function calculateTotal(): float {
        $total = array_sum($this->items);
        $tax = $this->taxStrategy ? $this->taxStrategy->calculateTax($total) : 0;
        return $total + $tax;
    }
}

// Usage
$cart = new ShoppingCart();
$cart->addItem("Book", 20);
$cart->addItem("Pen", 2);

// Using USA Tax Strategy
$cart->setTaxStrategy(new USATax());
echo "Total with USA Tax: $" . $cart->calculateTotal() . "\n"; // Total with tax

// Using UK Tax Strategy
$cart->setTaxStrategy(new UKTax());
echo "Total with UK Tax: $" . $cart->calculateTotal() . "\n"; // Total with tax

// Using No Tax Strategy
$cart->setTaxStrategy(new NoTax());
echo "Total with No Tax: $" . $cart->calculateTotal() . "\n"; // Total without tax
```