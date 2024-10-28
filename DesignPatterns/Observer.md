# Observer Design Pattern

The Observer design pattern is a behavioral design pattern that defines a one-to-many dependency between objects. When one object (the subject) changes its state, all its dependents (observers) are notified and updated automatically. This pattern is particularly useful in scenarios where a change in one object requires changes in others, promoting a decoupled design.

## Key Components of the Observer Pattern

1. **Subject**: The object that maintains a list of its observers and notifies them of any state changes.
2. **Observer**: An interface or abstract class that defines the method to be called when the subject changes.
3. **ConcreteSubject**: The implementation of the Subject that holds the state and notifies its observers.
4. **ConcreteObserver**: The implementation of the Observer that reacts to changes in the subject.

## Benefits of the Observer Pattern

- **Loose Coupling**: The subject and observers are loosely coupled, making the system easier to maintain and modify.
- **Dynamic Relationships**: Observers can be added or removed at runtime.
- **Broadcast Communication**: The subject can notify multiple observers about changes without knowing their details.

## Use Cases

- Event handling systems (e.g., GUI applications).
- Real-time data updates (e.g., stock market prices).
- Logging frameworks where multiple loggers need to respond to events.

## Implementation in PHP

Here’s an implementation of the Observer pattern in PHP:

```php
// Observer Interface
interface Observer {
    public function update($data);
}

// Subject Interface
interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}

// ConcreteSubject
class WeatherStation implements Subject {
    private $observers = [];
    private $temperature;

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $this->observers = array_filter($this->observers, function ($o) use ($observer) {
            return $o !== $observer;
        });
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this->temperature);
        }
    }

    public function setTemperature($temperature) {
        $this->temperature = $temperature;
        $this->notify();
    }
}

// ConcreteObserver
class TemperatureDisplay implements Observer {
    public function update($temperature) {
        echo "Temperature updated: $temperature °C\n";
    }
}

// Another ConcreteObserver
class TemperatureAlert implements Observer {
    private $threshold;

    public function __construct($threshold) {
        $this->threshold = $threshold;
    }

    public function update($temperature) {
        if ($temperature > $this->threshold) {
            echo "Alert! Temperature exceeded threshold: $temperature °C\n";
        }
    }
}

// Usage
$weatherStation = new WeatherStation();

$tempDisplay = new TemperatureDisplay();
$tempAlert = new TemperatureAlert(30);

$weatherStation->attach($tempDisplay);
$weatherStation->attach($tempAlert);

$weatherStation->setTemperature(25); // Normal temperature
$weatherStation->setTemperature(32); // Temperature exceeds threshold
```

## Example: News Subscription System

```php

// Observer Interface
interface Subscriber {
    public function update($article);
}

// Subject Interface
interface NewsAgencySubject {
    public function subscribe(Subscriber $subscriber);
    public function unsubscribe(Subscriber $subscriber);
    public function notify();
}

// ConcreteSubject
class NewsAgency implements NewsAgencySubject {
    private $subscribers = [];
    private $articles = [];

    public function subscribe(Subscriber $subscriber) {
        $this->subscribers[] = $subscriber;
    }

    public function unsubscribe(Subscriber $subscriber) {
        $this->subscribers = array_filter($this->subscribers, function ($s) use ($subscriber) {
            return $s !== $subscriber;
        });
    }

    public function notify() {
        foreach ($this->subscribers as $subscriber) {
            foreach ($this->articles as $article) {
                $subscriber->update($article);
            }
        }
    }

    public function publishArticle($article) {
        $this->articles[] = $article;
        $this->notify();
    }
}

// ConcreteObserver
class EmailSubscriber implements Subscriber {
    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function update($article) {
        echo "Email to {$this->email}: New article published: $article\n";
    }
}

// Another ConcreteObserver
class SMSSubscriber implements Subscriber {
    private $phoneNumber;

    public function __construct($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    public function update($article) {
        echo "SMS to {$this->phoneNumber}: New article published: $article\n";
    }
}

// Usage
$newsAgency = new NewsAgency();

$emailSubscriber = new EmailSubscriber("user@example.com");
$smsSubscriber = new SMSSubscriber("123-456-7890");

$newsAgency->subscribe($emailSubscriber);
$newsAgency->subscribe($smsSubscriber);

$newsAgency->publishArticle("Observer Pattern in PHP");
$newsAgency->publishArticle("Understanding Design Patterns");

```