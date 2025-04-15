# Securez

## A CQRS + DDD + Event sourcing + API Platform + Symfony + Vue3 + CI/CD + etc. tutorial

### 🧠 Key Ideas in DDD Symfony Architecture

- Group by domain, not by type (i.e., vertical slices, not horizontal layers).
- Each bounded context can have its own domain model and application logic.
- Infrastructure (Symfony-specific things like controllers) is pushed to the edges.
- Promote clean architecture principles: Domain → Application → Infrastructure.

### 📁 Recommended File Tree Structure

Here’s a practical and clean structure you can use for a Symfony project with DDD:

```
src/
├── Context/
│   ├── User/                       # Bounded Context
│   │   ├── Domain/
│   │   │   ├── Model/             # Domain Entities, Value Objects, Aggregates
│   │   │   ├── Repository/        # Domain repository interfaces
│   │   │   ├── Exception/
│   │   │   └── Service/           # Domain services
│   │   ├── Application/
│   │   │   ├── Command/           # Commands (DTOs)
│   │   │   ├── Handler/           # Command Handlers
│   │   │   ├── Query/
│   │   │   └── DTO/               # Application DTOs
│   │   ├── Infrastructure/
│   │   │   ├── Doctrine/          # Doctrine ORM implementation
│   │   │   ├── Api/               # Symfony Controllers / Http Adapters
│   │   │   ├── Event/             # Symfony Events / Listeners
│   │   │   └── Service/           # Infra services like Mailer, Clock, etc.
│   │   └── Tests/                 # Context-specific tests
│   └── Shared/                    # Shared kernel or utilities across contexts
├── Kernel.php                     # Symfony Kernel
```

### 💡 Example: Inside User/Domain/Model/

```
User/
├── Domain/
│   └── Model/
│       ├── User.php               # Aggregate Root
│       ├── UserId.php             # Value Object
│       ├── Email.php              # Value Object
│       └── UserStatus.php         # Enum
```

### 👑 Why This Is a Good Setup

- Scalable: Works well even if you add more contexts later (e.g., Billing, Inventory).
- Decoupled: Domain doesn’t depend on Symfony (no framework classes in Domain/).
- Testable: Core logic can be unit-tested without booting Symfony.
- Hexagonal: Supports clean/hexagonal architecture principles.

Would you like an example of how a User registration flow could look using this structure (command, handler, entity, controller, etc.)?

Perfect, let’s walk through a User Registration use case using DDD within Symfony. I’ll show you how it can be structured using the file tree we talked about.

### 🧩 Use Case: User Registration Flow

### 🧠 Steps

- A RegisterUserCommand is received.
- The RegisterUserHandler processes the command.
- It uses a domain service or factory to create a User entity.
- The entity is persisted via a UserRepository.
- An event could be dispatched (e.g. UserRegistered).

### 📁 File Tree for This Use Case

```
src/
└── Context/
└── User/
├── Domain/
│   ├── Model/
│   │   └── User.php
│   ├── Repository/
│   │   └── UserRepositoryInterface.php
│   └── Service/
│       └── UserFactory.php
├── Application/
│   ├── Command/
│   │   └── RegisterUserCommand.php
│   ├── Handler/
│   │   └── RegisterUserHandler.php
│   └── DTO/
│       └── RegisteredUserDto.php
├── Infrastructure/
│   ├── Doctrine/
│   │   └── DoctrineUserRepository.php
│   └── Api/
│       └── UserController.php
```

## 📌 Code Examples

## 1. RegisterUserCommand.php

```php
namespace App\Context\User\Application\Command;

final class RegisterUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
```

## 2. RegisterUserHandler.php

```php
namespace App\Context\User\Application\Handler;

use App\Context\User\Application\Command\RegisterUserCommand;
use App\Context\User\Domain\Repository\UserRepositoryInterface;
use App\Context\User\Domain\Service\UserFactory;

final class RegisterUserHandler
{
    public function __construct(
        private UserFactory $userFactory,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $user = $this->userFactory->create($command->email, $command->password);
        $this->userRepository->save($user);
    }
}
```

## 3. UserFactory.php

```php
namespace App\Context\User\Domain\Service;

use App\Context\User\Domain\Model\User;
use Symfony\Component\Uid\Uuid;

final class UserFactory
{
    public function create(string $email, string $plainPassword): User
    {
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        return new User(Uuid::v4(), $email, $hashedPassword);
    }
}
```

## 4. User.php (Entity)

```php
namespace App\Context\User\Domain\Model;

use Symfony\Component\Uid\Uuid;

final class User
{
    public function __construct(
        private Uuid $id,
        private string $email,
        private string $hashedPassword
    ) {}

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
```

## 5. UserRepositoryInterface.php

```php
namespace App\Context\User\Domain\Repository;

use App\Context\User\Domain\Model\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
}
```

## 6. DoctrineUserRepository.php

```php
namespace App\Context\User\Infrastructure\Doctrine;

use App\Context\User\Domain\Model\User;
use App\Context\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
```

## 7. UserController.php

```php
namespace App\Context\User\Infrastructure\Api;

use App\Context\User\Application\Command\RegisterUserCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    public function register(Request $request, MessageBusInterface $commandBus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $command = new RegisterUserCommand($data['email'], $data['password']);
        $commandBus->dispatch($command);

        return new JsonResponse(['status' => 'ok'], 201);
    }
}
```

## ✅ Benefits of This Flow

- The controller is dumb — it just delegates.
- Application layer contains orchestration logic.
- Domain layer encapsulates rules and business objects.
- Easily testable without Symfony (just PHP).
- Works great with Symfony Messenger for async processing.

If you want, I can add:

- Event dispatching (e.g., UserRegisteredEvent)
- Domain validations or exceptions
- Tests (unit/functional)

Want to go deeper on any of those?

Nice 😎 Let's level it up! Since you're open to going deeper, I'll enhance the User Registration use case with:

    ✅ Domain validation (e.g., email format check, password rules)
    📣 Domain Event: UserRegisteredEvent
    🧪 Unit test for RegisterUserHandler

## 🧱 1. Domain Validation (Value Object: Email)

Instead of storing a raw string, we encapsulate email rules in a Value Object.

Email.php

```php
namespace App\Context\User\Domain\Model;

use InvalidArgumentException;

final class Email
{
    public function __construct(private string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email: $value");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
```

Update the User entity to use Email instead of string.

User.php (updated)

```php
final class User
{
    public function __construct(
        private Uuid $id,
        private Email $email,
        private string $hashedPassword
    ) {}

    public function getEmail(): Email
    {
        return $this->email;
    }
}
```

## 🔔 2. Domain Event: UserRegisteredEvent

UserRegisteredEvent.php

```php
namespace App\Context\User\Domain\Event;

use App\Context\User\Domain\Model\User;

final class UserRegisteredEvent
{
    public function __construct(public readonly User $user) {}
}
```

Update RegisterUserHandler.php to Dispatch Event

We assume Symfony Messenger handles domain events as messages. Inject MessageBusInterface.

```php
use Symfony\Component\Messenger\MessageBusInterface;
use App\Context\User\Domain\Event\UserRegisteredEvent;

final class RegisterUserHandler
{
    public function __construct(
        private UserFactory $userFactory,
        private UserRepositoryInterface $userRepository,
        private MessageBusInterface $eventBus
    ) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $user = $this->userFactory->create($command->email, $command->password);
        $this->userRepository->save($user);

        $this->eventBus->dispatch(new UserRegisteredEvent($user));
    }
}
```

You can create an event listener or subscriber to handle this event (e.g., send welcome email).

## 🧪 3. Unit Test for RegisterUserHandler

You can use PHPUnit to test your handler in isolation.

RegisterUserHandlerTest.php

```php
use PHPUnit\Framework\TestCase;
use App\Context\User\Application\Command\RegisterUserCommand;
use App\Context\User\Application\Handler\RegisterUserHandler;
use App\Context\User\Domain\Service\UserFactory;
use App\Context\User\Domain\Model\Email;
use App\Context\User\Domain\Model\User;
use App\Context\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterUserHandlerTest extends TestCase
{
    public function testItRegistersUserAndDispatchesEvent(): void
    {
        $factory = new UserFactory();
        $repository = $this->createMock(UserRepositoryInterface::class);
        $eventBus = $this->createMock(MessageBusInterface::class);

        $repository->expects($this->once())->method('save');
        $eventBus->expects($this->once())->method('dispatch');

        $handler = new RegisterUserHandler($factory, $repository, $eventBus);

        $command = new RegisterUserCommand('test@example.com', 'securePassword123');
        $handler($command); // using __invoke
    }
}
```

## 🚀 What's Next?

Let me know if you want to go even further:

    ✅ Event subscriber that sends a real email
    🔐 Password strength policy / password VO
    💾 Add projection (e.g., read model for user list)
    🧱 Full test suite for domain and application layers

Would you like one of those next?

Awesome — let's go full DDD beast mode 💪 Here comes the complete set:

## ✅ 1. Event Subscriber That Sends a Real Email

We'll handle UserRegisteredEvent and send a welcome email via Symfony's Mailer component.

UserRegisteredSubscriber.php

```php
namespace App\Context\User\Infrastructure\Event;

use App\Context\User\Domain\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class UserRegisteredSubscriber implements EventSubscriberInterface
{
public function __construct(private MailerInterface $mailer) {}

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $user = $event->user;

        $email = (new Email())
            ->from('noreply@yourapp.com')
            ->to((string) $user->getEmail())
            ->subject('Welcome to Our App!')
            ->text('Thanks for signing up!');

        $this->mailer->send($email);
    }
}
```

Don’t forget to tag it as an event subscriber in services.yaml:

```
App\Context\User\Infrastructure\Event\UserRegisteredSubscriber:
tags: [ 'kernel.event_subscriber' ]
```

## 🔐 2. Password VO with Strength Validation

Password.php (Value Object)

```php
namespace App\Context\User\Domain\Model;

use InvalidArgumentException;

final class Password
{
    public function __construct(private string $plainText)
    {
        if (strlen($plainText) < 8 || !preg_match('/[A-Z]/', $plainText)) {
            throw new InvalidArgumentException('Password must be at least 8 characters long and contain an uppercase letter.');
        }
    }

    public function hash(): string
    {
        return password_hash($this->plainText, PASSWORD_DEFAULT);
    }

    public function getRaw(): string
    {
        return $this->plainText;
    }
}
```

Use it in the factory:

```php
public function create(string $email, string $plainPassword): User
{
    $emailVO = new Email($email);
    $passwordVO = new Password($plainPassword);

    return new User(Uuid::v4(), $emailVO, $passwordVO->hash());
}
```

## 💾 3. Read Model / Projection (for listing users)

We’ll create a DTO for listing, and a read model (projection) via a repository.
UserReadModel.php

```php
namespace App\Context\User\Application\DTO;

final class UserReadModel
{
    public function __construct(
        public string $id,
        public string $email
    ) {}
}
```

UserReadRepositoryInterface.php

```php
namespace App\Context\User\Application\Query;

use App\Context\User\Application\DTO\UserReadModel;

interface UserReadRepositoryInterface
{
    /** @return UserReadModel[] */
    public function findAll(): array;
}
```

DoctrineUserReadRepository.php

```php
namespace App\Context\User\Infrastructure\Doctrine;

use App\Context\User\Application\DTO\UserReadModel;
use App\Context\User\Application\Query\UserReadRepositoryInterface;
use Doctrine\DBAL\Connection;

final class DoctrineUserReadRepository implements UserReadRepositoryInterface
{
    public function __construct(private Connection $db) {}

    public function findAll(): array
    {
        $rows = $this->db->fetchAllAssociative('SELECT id, email FROM user');

        return array_map(fn ($row) => new UserReadModel($row['id'], $row['email']), $rows);
    }
}
```

You could also query from an ElasticSearch or dedicated read table if you're going CQRS-heavy.

## 🧪 4. Full Test Suite Structure

Tests structure suggestion:

```
tests/
├── Context/
│   └── User/
│       ├── Application/
│       │   └── Handler/
│       │       └── RegisterUserHandlerTest.php
│       ├── Domain/
│       │   ├── Model/
│       │   │   └── EmailTest.php
│       │   └── Service/
│       │       └── UserFactoryTest.php
│       └── Infrastructure/
│           └── Event/
│               └── UserRegisteredSubscriberTest.php
```

Example: EmailTest.php

```php
use PHPUnit\Framework\TestCase;
use App\Context\User\Domain\Model\Email;

class EmailTest extends TestCase
{
    public function testValidEmail(): void
    {
        $email = new Email('john@example.com');
        $this->assertEquals('john@example.com', $email->getValue());
    }

    public function testInvalidEmailThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Email('not-an-email');
    }
}
```

## 🚀 Wrapping Up

You now have:

    💌 Event-driven mail notifications
    🔐 Password VO with policy enforcement
    🧾 Read model for listing users
    🧪 Full test coverage strategy

Want to go further with:

    ✅ CQRS separation (Command Bus / Query Bus)
    🪶 Event sourcing
    🏗️ Modular Monolith / multi-bounded-context architecture
    🧩 Integrating API Platform in this stack?

Let me know your next ambition — I’m ready to dive in!

Absolutely! Here's a composer.json file tailored for a Symfony app with DDD structure, covering:

    ✅ Symfony core + Messenger + Mailer
    🔐 Doctrine ORM + DBAL
    🧪 PHPUnit for testing
    🧾 Symfony UID (for UUIDs)
    🧩 Optional: Symfony Validator (for future VO validation)

📦 composer.json

```json
{
    "name": "yourvendor/yourapp",
    "type": "project",
    "require": {
        "php": ">=8.2",
        "ext-ctype": "*",
        "ext-iconv": "*",
    
        "symfony/framework-bundle": "^6.4 || ^7.0",
        "symfony/mailer": "^6.4 || ^7.0",
        "symfony/messenger": "^6.4 || ^7.0",
        "symfony/uid": "^6.4 || ^7.0",
        "symfony/console": "^6.4 || ^7.0",
        "symfony/http-kernel": "^6.4 || ^7.0",
        "symfony/http-foundation": "^6.4 || ^7.0",
        "symfony/dependency-injection": "^6.4 || ^7.0",
        "symfony/event-dispatcher": "^6.4 || ^7.0",
        "symfony/routing": "^6.4 || ^7.0",
        "symfony/config": "^6.4 || ^7.0",
    
        "symfony/validator": "^6.4 || ^7.0",         // Optional, useful for validating DTOs/VOs
        "doctrine/orm": "^2.19",
        "doctrine/doctrine-bundle": "^2.10",
        "doctrine/doctrine-migrations-bundle": "^3.3"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.3",
        "symfony/test-pack": "^1.0",
        "symfony/var-dumper": "^6.4 || ^7.0",
        "symfony/debug-bundle": "^6.4 || ^7.0",
        "fakerphp/faker": "^1.21"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "App\\Tests\\": "tests/"
        }
    },
    "scripts": {
        "test": "phpunit"
    },
    "extra": {
        "symfony": {
            "allow-contrib": true,
            "require": "6.4.*"
        }
    }
}
```

## 🛠️ Installation Command

To create the project with this setup:

```bash
composer create-project symfony/skeleton my_project
cd my_project
composer require symfony/mailer symfony/messenger symfony/uid symfony/validator
composer require doctrine/orm doctrine/doctrine-bundle doctrine/doctrine-migrations-bundle
composer require --dev phpunit/phpunit symfony/test-pack fakerphp/faker
```

## ⚙️ Notes

- Symfony 6.4 is LTS and compatible with Symfony 7.x where noted.
- You can use Symfony Flex to streamline config and directory structure.
- If you're using Messenger for both commands and events, this setup will work well.
- You can easily plug in API Platform later without changing this file much.

Let me know if you're using Docker or want me to scaffold the base Symfony config (.env, services.yaml, etc.) for this DDD project too.

Let's get it — we’re going full DDD mode with CQRS, Event Sourcing, Modular Architecture, and API Platform integration. Here's the breakdown, step by step:

## 1. ✅ CQRS Separation: Command Bus / Query Bus

Symfony Messenger is already your Command Bus. To split read from write:

### 📁 Command Side (Write)

- Application\Command\*
- Application\Handler\*
- Uses Messenger as Command Bus
- Commands mutate state and may emit events

```php
$command = new RegisterUserCommand(...);
$commandBus->dispatch($command); // Messenger
```

### 📁 Query Side (Read)

Introduce a Query Bus, similar to Command Bus.

🔧 Create a QueryBusInterface and QueryHandlerInterface

```php
namespace App\Shared\Application\Query;

interface QueryHandlerInterface
{
    public function __invoke(object $query): mixed;
}
```

```php
namespace App\Shared\Application\Query;

interface QueryBusInterface
{
    public function ask(object $query): mixed;
}
```

### ✅ Simple In-Memory QueryBus Implementation

```php
final class InMemoryQueryBus implements QueryBusInterface
{
    public function __construct(private iterable $handlers) {}

    public function ask(object $query): mixed
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof QueryHandlerInterface && (new \ReflectionParameter([$handler, '__invoke'], 0))->getType()->getName() === $query::class) {
                return $handler($query);
            }
        }

        throw new \RuntimeException('No handler found for query ' . get_class($query));
    }
}
```

Register it as a service tagged with query_handler.

## 2. 🪶 Event Sourcing (Intro)

For full event sourcing, you'd want to store domain events in a separate table and replay them.

Here’s a minimal sketch:

- Persist all Domain Events in a table (event_store)
- Rebuild state from events (aggregate reconstitution)
- Use Prooph or Broadway for full-blown support

Want a full custom lightweight implementation? I can scaffold one.

## 3. 🏗️ Modular Monolith / Multi-Bounded-Context

You already have a modular structure. Here’s how to organize at scale:

```php
src/
├── Context/
│   ├── User/
│   │   ├── Domain/
│   │   ├── Application/
│   │   ├── Infrastructure/
│   │   └── UI/Http/
│   └── Catalog/
│       ├── Domain/
│       ├── Application/
│       └── Infrastructure/
└── Shared/
├── Domain/
├── Application/
└── Infrastructure/
```

You can register services per context with PSR-4 autoloading.

Example in services.yaml:

```php
App\Context\User\:
resource: '../src/Context/User/*'
exclude: '../src/Context/User/{Domain,Tests}'
```

## 4. 🧩 API Platform Integration

Install:

```
composer require api-platform
```

Use DTOs as input/output, and application services as your bridges.
Example: Register User via API Platform

```php
namespace App\Context\User\UI\Api;

use App\Context\User\Application\Command\RegisterUserCommand;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    operations: [new Post()]
)]
final class RegisterUserInput
{
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Length(min: 8)]
    public string $password;

    public function __construct(
        private MessageBusInterface $commandBus
    ) {}

    public function __invoke(): void
    {
        $this->commandBus->dispatch(new RegisterUserCommand($this->email, $this->password));
    }
}
```

## 💡 Next Steps You Can Take:

- Add OpenAPI docs with custom filters
- Integrate JWT auth with lexik/jwt-authentication-bundle
- Add event replay CLI for ES
- Set up custom annotations or attributes for commands/queries

Want me to:

- Scaffold a custom lightweight event store?
- Add Docker + Makefile + example config?
- Set up GraphQL support via API Platform?

Let’s build it brick by brick — your call!

Perfect — let's knock out the Next Steps one by one, then dive into the “Want me to…” section. Here we go:

## ✅ Next Steps Implementation

## 1. 📘 OpenAPI Docs with API Platform

API Platform automatically generates OpenAPI docs at /docs.

To customize them (e.g., add tags or descriptions):

```php
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            description: 'Register a new user',
            summary: 'Creates a new user',
            openapiContext: [
                'tags' => ['User'],
            ]
        )
    ]
)]
class RegisterUserInput { ... }
```

Customization Tip: Install NelmioBundle for more detailed control if needed.

## 2. 🔐 JWT Auth with LexikJWTAuthenticationBundle

Install:

```bash
composer require lexik/jwt-authentication-bundle
```

Generate keys:

```bash
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

Add to .env:

```dotenv
JWT_PASSPHRASE=your_passphrase
```

Configure bundle in config/packages/lexik_jwt_authentication.yaml:

```yaml
lexik_jwt_authentication:
secret_key: '%kernel.project_dir%/config/jwt/private.pem'
public_key: '%kernel.project_dir%/config/jwt/public.pem'
pass_phrase: '%env(JWT_PASSPHRASE)%'
token_ttl: 3600
```

Create login endpoint using API Platform or custom controller, or expose /api/login_check.

## 3. 🔁 Event Replay CLI (for Event Sourcing)

We’ll build a custom command to replay domain events from the store:

```php
namespace App\Shared\Infrastructure\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:replay-events')]
class ReplayEventsCommand extends Command
{
    public function __construct(private EventStore $store, private EventDispatcherInterface $dispatcher) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->store->all() as $event) {
            $this->dispatcher->dispatch($event);
        }

        $output->writeln('All events replayed.');
        return Command::SUCCESS;
    }
}
```

This assumes you’ve got an EventStore interface + implementation — coming up soon.

## 4. 🧩 Custom Attributes/Annotations for Command/Query

Let's define some PHP 8 attributes:

```php
namespace App\Shared\Infrastructure\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CommandHandler {}

#[\Attribute(\Attribute::TARGET_CLASS)]
class QueryHandler {}

Now decorate your handlers:

#[CommandHandler]
final class RegisterUserHandler { ... }

#[QueryHandler]
final class FindUserByIdHandler { ... }
```

You can then scan services by attribute via a compiler pass or tagged iterator.

Next up: let’s handle the “Want me to…” section — we’ll:

    ✅ Scaffold a lightweight event store
    ✅ Set up Docker + Makefile
    ✅ Add GraphQL via API Platform

Jumping in next...

Nice 😎 Let’s finish the stack with the "Want me to…" items.

## 🪶 1. Lightweight Event Store (Custom)

We’ll store domain events in a simple DB table and replay them later.

### 🎯 Goal

- Append-only storage
- Serializable events
- JSON format
- Works with the app:replay-events command

### 📁 Domain Interface

```php
namespace App\Shared\Domain\Event;

interface DomainEvent
{
    public function occurredAt(): \DateTimeImmutable;
}
```

### 📁 Event Store

```php
namespace App\Shared\Infrastructure\Event;

use Doctrine\DBAL\Connection;
use Symfony\Component\Serializer\SerializerInterface;
use App\Shared\Domain\Event\DomainEvent;

final class DoctrineEventStore
{
    public function __construct(
        private Connection $db,
        private SerializerInterface $serializer
    ) {}

    public function append(DomainEvent $event): void
    {
        $this->db->insert('event_store', [
            'type' => $event::class,
            'payload' => $this->serializer->serialize($event, 'json'),
            'occurred_at' => $event->occurredAt()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return DomainEvent[]
     */
    public function all(): array
    {
        $rows = $this->db->fetchAllAssociative('SELECT * FROM event_store ORDER BY occurred_at ASC');

        return array_map(function ($row) {
            return $this->serializer->deserialize($row['payload'], $row['type'], 'json');
        }, $rows);
    }
}
```

### 📁 DB Migration

```sql
CREATE TABLE event_store (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    payload JSON NOT NULL,
    occurred_at DATETIME NOT NULL
);
```

### 📦 Register Serializer

In services.yaml:

```yaml
Symfony\Component\Serializer\SerializerInterface: '@serializer'
```

## 🐳 2. Docker + Makefile Setup

### 📁 docker-compose.yml

```yaml
version: '3.8'

services:
    php:
        build:
            context: .
            dockerfile: Dockerfile
        volumes:
            - .:/app
        working_dir: /app
        depends_on:
            - db

    db:
        image: mysql:8.0
        environment:
            MYSQL_DATABASE: symfony
            MYSQL_USER: symfony
            MYSQL_PASSWORD: secret
            MYSQL_ROOT_PASSWORD: root
        ports:
            - "3306:3306"

    nginx:
        image: nginx:alpine
        ports:
            - "8080:80"
        volumes:
            - .:/app
            - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
```

### 📁 Dockerfile

```dockerfile
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
git unzip libzip-dev zip \
&& docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
```

Compose the image:
```
 set -a && source .env.local && docker-compose up --build  -d --wait
```

### 📁 Makefile

```makefile
.PHONY: start stop test

start:
docker-compose up -d --build

stop:
docker-compose down

bash:
docker-compose exec php bash

test:
docker-compose exec php ./vendor/bin/phpunit
```

## 🔮 3. GraphQL Support via API Platform

Install GraphQL:

```bash
composer require api-platform:graphql
```

This gives you /graphql out of the box.

### ✅ Example Resource

```php
#[ApiResource(
    graphql: ['item_query', 'collection_query']
)]
final class UserReadModel
{
    public string $id;
    public string $email;
}
```

You can now query via:

```graphql
query {
    userReadModels {
        id
        email
    }
}
```

## 🎉 All Done!

You now have:

    ✅ OpenAPI & GraphQL APIs
    ✅ JWT auth
    ✅ Modular CQRS
    ✅ Event sourcing w/ replay
    ✅ Custom command/query attributes
    ✅ Dockerized dev env
    ✅ Makefile for workflows
