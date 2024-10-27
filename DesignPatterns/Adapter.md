The Adapter Design Pattern is a structural design pattern that allows incompatible interfaces to work together. It acts as a bridge between two incompatible interfaces, enabling them to communicate. This is particularly useful when you want to integrate new functionality into existing code without altering the original codebase.

## Key Components of the Adapter Pattern

1. **Target Interface**: This is the interface that clients expect to work with. It defines the domain-specific interface that the client uses.

2. **Adaptee**: This is an existing class with a different interface that needs to be adapted. It contains the actual implementation of the functionality.

3. **Adapter**: This is the class that implements the target interface and translates the requests from the target interface to the adaptee's interface.

4. **Client**: This is the class that interacts with the target interface. The client is unaware of the adapter and adaptee, allowing it to work seamlessly with the adapted interface.

## Benefits of the Adapter Pattern

- **Reusability**: Existing code can be reused with minimal changes, facilitating the integration of new features.

- **Decoupling**: It reduces dependencies between components by providing a standard interface.

- **Flexibility**: You can easily swap out different implementations without affecting the client code.

## Use Cases

- **Legacy Integration**: When integrating with legacy systems that have incompatible interfaces.

- **Third-Party Libraries**: When using third-party libraries that don’t conform to your system’s interface.

- **Improving APIs**: When you want to create a more user-friendly API that wraps complex implementations.

## Implementation in PHP
Let's illustrate the Adapter pattern with an example of a MediaPlayer that can play different types of audio files, but we want to adapt an existing class that plays only a specific type of file.

```php
<?php

// Target Interface
interface MediaPlayer {
    public function play($audioType, $fileName);
}

// Adaptee
class AdvancedMediaPlayer {
    public function playMp3($fileName) {
        echo "Playing MP3 file: " . $fileName . PHP_EOL;
    }

    public function playVlc($fileName) {
        echo "Playing VLC file: " . $fileName . PHP_EOL;
    }
}

// Adapter
class MediaAdapter implements MediaPlayer {
    private $advancedMediaPlayer;

    public function __construct($audioType) {
        if ($audioType === 'vlc') {
            $this->advancedMediaPlayer = new AdvancedMediaPlayer();
        }
    }

    public function play($audioType, $fileName) {
        if ($audioType === 'mp3') {
            (new AdvancedMediaPlayer())->playMp3($fileName);
        } elseif ($audioType === 'vlc') {
            $this->advancedMediaPlayer->playVlc($fileName);
        }
    }
}

// Client
class AudioPlayer implements MediaPlayer {
    private $mediaAdapter;

    public function play($audioType, $fileName) {
        if ($audioType === 'mp3' || $audioType === 'vlc') {
            if ($audioType === 'vlc') {
                $this->mediaAdapter = new MediaAdapter($audioType);
                $this->mediaAdapter->play($audioType, $fileName);
            } else {
                echo "Playing MP3 file: " . $fileName . PHP_EOL;
            }
        } else {
            echo "Invalid media type: " . $audioType . PHP_EOL;
        }
    }
}

// Usage
$audioPlayer = new AudioPlayer();
$audioPlayer->play('mp3', 'song.mp3');
$audioPlayer->play('vlc', 'movie.vlc');
$audioPlayer->play('avi', 'video.avi');

?>
```

## Explanation of the Code

- **Target Interface (MediaPlayer)**: Defines the interface for the media player, which the client uses.

- **Adaptee (AdvancedMediaPlayer)**: This class contains methods to play specific audio formats (mp3 and vlc).

- **Adapter (MediaAdapter)**: Implements the MediaPlayer interface and adapts calls to the appropriate methods in AdvancedMediaPlayer. It decides which method to call based on the audio type.

- **Client (AudioPlayer)**: The client uses the MediaPlayer interface and interacts with it without knowing about the adapter or adaptee. It delegates the playing of vlc files to the MediaAdapter.

## Conclusion

The Adapter Design Pattern is a powerful way to enable cooperation between incompatible interfaces. It promotes code reusability, flexibility, and decoupling, making it easier to integrate and manage different components in a system. This pattern is especially useful in legacy system integration, API design, and third-party library integration.

# Adapter Design Pattern Example: Payment Processing

## Scenario: Payment Processing

Imagine you have an existing application that can process payments through a simple `PaymentProcessor` interface. However, you also want to integrate a legacy payment system that uses a different interface.

### Key Components

1. **Target Interface**: `PaymentProcessor` (the interface the new application will use).
2. **Adaptee**: `LegacyPaymentSystem` (the existing class with a different interface).
3. **Adapter**: `PaymentAdapter` (the class that implements `PaymentProcessor` and adapts calls to the `LegacyPaymentSystem`).
4. **Client**: The application that uses the `PaymentProcessor`.

### Code Implementation

Here's how you can implement this scenario in PHP:

```php
<?php

// Target Interface
interface PaymentProcessor {
    public function processPayment($amount);
}

// Adaptee
class LegacyPaymentSystem {
    public function makePayment($amount) {
        echo "Processing payment of $$amount using Legacy Payment System." . PHP_EOL;
    }
}

// Adapter
class PaymentAdapter implements PaymentProcessor {
    private $legacyPaymentSystem;

    public function __construct() {
        $this->legacyPaymentSystem = new LegacyPaymentSystem();
    }

    public function processPayment($amount) {
        // Adapting the call from PaymentProcessor to LegacyPaymentSystem
        $this->legacyPaymentSystem->makePayment($amount);
    }
}

// Client
class PaymentService {
    private $paymentProcessor;

    public function __construct(PaymentProcessor $paymentProcessor) {
        $this->paymentProcessor = $paymentProcessor;
    }

    public function makePayment($amount) {
        $this->paymentProcessor->processPayment($amount);
    }
}

// Usage
// Using the adapter to connect the new application to the legacy system
$adapter = new PaymentAdapter();
$paymentService = new PaymentService($adapter);

// Processing a payment
$paymentService->makePayment(100);
$paymentService->makePayment(250);

?>
```

## Explanation of the Code

- **Target Interface (`PaymentProcessor`)**: This interface defines the method `processPayment`,  which the client will use to process payments.

- **Adaptee (`LegacyPaymentSystem`)**: This class has a method `makePayment`, which processes payments in its own way, not compatible with the new application.

- **Adapter (`PaymentAdapter`)**: This class implements the `PaymentProcessor` interface and contains an instance of `LegacyPaymentSystem`. The `processPayment` method calls `makePayment` on the adaptee, thus adapting the interface.

- **Client (`PaymentService`)**: This class uses the `PaymentProcessor` interface to process payments. It remains agnostic of the underlying implementation details.