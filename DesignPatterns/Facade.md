# Facade Design Pattern

The Facade Design Pattern is a structural design pattern that provides a simplified interface to a complex subsystem. By encapsulating the complexities of the subsystem, the Facade pattern makes it easier to interact with the system, allowing clients to use the subsystem with minimal knowledge of its intricacies.

## Key Components of the Facade Pattern

1. **Facade**: The main class that provides a simplified interface to the complex system. It delegates requests to the appropriate classes within the subsystem.

2. **Subsystem Classes**: These are the classes that implement the complex functionality of the system. The facade interacts with these classes to perform the required operations.

## Benefits of the Facade Pattern

- **Simplification**: It reduces the complexity of interacting with a system by providing a simple interface.
- **Decoupling**: Clients are decoupled from the subsystem, allowing for easier changes and maintenance.
- **Ease of Use**: It makes the system easier to use for clients who need to perform operations without delving into the subsystem's details.

## Use Cases

- **API Integration**: When integrating with complex APIs, a facade can simplify the interaction process.
- **Library Management**: When dealing with a library that has numerous classes and functions, a facade can streamline the usage.
- **Complex Systems**: Any situation where a complex system needs to be simplified for user interaction, such as multimedia systems, game engines, etc.

## Implementation in PHP

Let’s illustrate the Facade pattern with an example of a home theater system, where various components (like a DVD player, projector, and sound system) need to work together, but the client interacts only with the Facade.

### Code Implementation

```php
<?php

// Subsystem Classes
class DVDPlayer {
    public function on() {
        echo "DVD Player is ON." . PHP_EOL;
    }

    public function play($movie) {
        echo "Playing '$movie'." . PHP_EOL;
    }

    public function stop() {
        echo "DVD Player is stopped." . PHP_EOL;
    }

    public function off() {
        echo "DVD Player is OFF." . PHP_EOL;
    }
}

class Projector {
    public function on() {
        echo "Projector is ON." . PHP_EOL;
    }

    public function setInput($input) {
        echo "Projector input set to '$input'." . PHP_EOL;
    }

    public function off() {
        echo "Projector is OFF." . PHP_EOL;
    }
}

class SoundSystem {
    public function on() {
        echo "Sound System is ON." . PHP_EOL;
    }

    public function setVolume($volume) {
        echo "Sound System volume set to $volume." . PHP_EOL;
    }

    public function off() {
        echo "Sound System is OFF." . PHP_EOL;
    }
}

// Facade
class HomeTheaterFacade {
    private $dvdPlayer;
    private $projector;
    private $soundSystem;

    public function __construct(DVDPlayer $dvdPlayer, Projector $projector, SoundSystem $soundSystem) {
        $this->dvdPlayer = $dvdPlayer;
        $this->projector = $projector;
        $this->soundSystem = $soundSystem;
    }

    public function watchMovie($movie) {
        echo "Getting ready to watch a movie..." . PHP_EOL;
        $this->projector->on();
        $this->projector->setInput('DVD');
        $this->soundSystem->on();
        $this->soundSystem->setVolume(5);
        $this->dvdPlayer->on();
        $this->dvdPlayer->play($movie);
    }

    public function endMovie() {
        echo "Shutting down the home theater..." . PHP_EOL;
        $this->dvdPlayer->stop();
        $this->dvdPlayer->off();
        $this->soundSystem->off();
        $this->projector->off();
    }
}

// Usage
$dvdPlayer = new DVDPlayer();
$projector = new Projector();
$soundSystem = new SoundSystem();
$homeTheater = new HomeTheaterFacade($dvdPlayer, $projector, $soundSystem);

// Watch a movie
$homeTheater->watchMovie("Inception");

// End the movie
$homeTheater->endMovie();

?>
```

# Facade Design Pattern Example: Banking System

The Facade Design Pattern provides a simplified interface to a complex subsystem. In this example, we will illustrate a banking system where various services interact, such as account management, loan processing, and transaction handling. The Facade will simplify the interface for clients wanting to perform banking operations.

## Key Components

1. **Facade**: The `BankFacade` class that provides a simplified interface for common banking operations.
  
2. **Subsystem Classes**: Classes representing various components of the banking system, like `AccountService`, `LoanService`, and `TransactionService`.

## Code Implementation

Here’s how you can implement this scenario in PHP:

```php
<?php

// Subsystem Classes
class AccountService {
    public function createAccount($accountName) {
        echo "Account '$accountName' created." . PHP_EOL;
    }

    public function getAccountDetails($accountName) {
        echo "Details for account '$accountName': Balance: $1000." . PHP_EOL;
    }
}

class LoanService {
    public function applyForLoan($amount) {
        echo "Loan application for $$amount submitted." . PHP_EOL;
    }

    public function getLoanStatus($loanId) {
        echo "Status for loan ID '$loanId': Approved." . PHP_EOL;
    }
}

class TransactionService {
    public function deposit($accountName, $amount) {
        echo "Deposited $$amount to account '$accountName'." . PHP_EOL;
    }

    public function withdraw($accountName, $amount) {
        echo "Withdrew $$amount from account '$accountName'." . PHP_EOL;
    }
}

// Facade
class BankFacade {
    private $accountService;
    private $loanService;
    private $transactionService;

    public function __construct() {
        $this->accountService = new AccountService();
        $this->loanService = new LoanService();
        $this->transactionService = new TransactionService();
    }

    public function createAccount($accountName) {
        $this->accountService->createAccount($accountName);
    }

    public function depositMoney($accountName, $amount) {
        $this->transactionService->deposit($accountName, $amount);
    }

    public function withdrawMoney($accountName, $amount) {
        $this->transactionService->withdraw($accountName, $amount);
    }

    public function applyForLoan($amount) {
        $this->loanService->applyForLoan($amount);
    }

    public function getAccountDetails($accountName) {
        $this->accountService->getAccountDetails($accountName);
    }
}

// Usage
$bankFacade = new BankFacade();

// Creating an account
$bankFacade->createAccount("John Doe");

// Depositing money
$bankFacade->depositMoney("John Doe", 500);

// Withdrawing money
$bankFacade->withdrawMoney("John Doe", 200);

// Applying for a loan
$bankFacade->applyForLoan(10000);

// Getting account details
$bankFacade->getAccountDetails("John Doe");

?>
```