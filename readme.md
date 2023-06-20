
# Application de demande de congé

Ce projet est une application de demande de congé qui permet aux employés de soumettre des demandes de congé et aux gestionnaires de les approuver ou de les rejeter. L'architecture utilisée est l'architecture Hexagonale combinée à la DDD pour garantir une conception modulaire, maintenable et orientée métier.

## Structure du projet

Le projet est organisé de la manière suivante :

```
src/
├── Domain/
│   ├── Entities/
│   │   ├── Employee.php
│   │   └── LeaveRequest.php
│   ├── Ports/
│   │   ├── Repositories/
│   │   │   ├── EmployeeRepositoryPort.php
│   │   │   └── LeaveRequestRepositoryPort.php
│   │   └── Services/
│   │       └── EmailNotificationPort.php
│   └── UseCases/
│       ├── CreateLeaveRequestUseCase.php
│       ├── ApproveLeaveRequestUseCase.php
│       └── RejectLeaveRequestUseCase.php
└── Infrastructure/
    ├── Adapters/
    │   ├── Repository/
    │   │   ├── DatabaseEmployeeRepository.php
    │   │   └── DatabaseLeaveRequestRepository.php
    │   └── Services/
    │       └── EmailNotificationAdapter.php
```

- Le répertoire `Domain` contient les entités métier qui représentent les employés (`Employee.php`) et les demandes de congé (`LeaveRequest.php`). Il comprend également le répertoire `Ports` qui contient les interfaces des ports pour les repositories (`Repositories`) et les services externes (`Services`).

- Le répertoire `Infrastructure` contient les adaptateurs qui implémentent les ports définis dans le domaine. Le sous-répertoire `Adapters/Repository` contient les adaptateurs pour les repositories (`DatabaseEmployeeRepository.php` et `DatabaseLeaveRequestRepository.php`), tandis que le sous-répertoire `Adapters/Services` contient l'adaptateur pour le service de notification par e-mail (`EmailNotificationAdapter.php`).

## Utilisation des cas d'utilisation

Voici un exemple d'utilisation des cas d'utilisation dans notre application :

```php
// Création d'une demande de congé pour un employé
$createLeaveRequestUseCase = new CreateLeaveRequestUseCase(
    $employeeRepository,
    $leaveRequestRepository,
    $emailNotificationAdapter
);

$employeeId = 123;
$startDate = '2023-07-01';
$endDate = '2023-07-05';
$leaveType = 'Vacation';

try {
    $createLeaveRequestUseCase->execute($employeeId, $startDate, $endDate, $leaveType);
    echo "Demande de congé créée avec succès !";
} catch (Exception $e) {
    echo "Erreur lors de la création de la demande de congé : " . $e->getMessage();
}

// Approbation d'une demande de congé par un gestionnaire
$approveLeaveRequestUseCase = new ApproveLeaveRequestUseCase(
    $leaveRequestRepository,
    $emailNotificationAdapter
);

$leaveRequestId = 456;

try {
    $approveLeaveRequestUseCase->execute($leaveRequestId);
    echo "Demande de congé approuvée avec succès !";
} catch (Exception $e) {
    echo "Erreur

 lors de l'approbation de la demande de congé : " . $e->getMessage();
}

// Rejet d'une demande de congé par un gestionnaire
$rejectLeaveRequestUseCase = new RejectLeaveRequestUseCase(
    $leaveRequestRepository,
    $emailNotificationAdapter
);

$leaveRequestId = 789;

try {
    $rejectLeaveRequestUseCase->execute($leaveRequestId);
    echo "Demande de congé rejetée avec succès !";
} catch (Exception $e) {
    echo "Erreur lors du rejet de la demande de congé : " . $e->getMessage();
}
```

Cet exemple montre comment utiliser les cas d'utilisation `CreateLeaveRequestUseCase`, `ApproveLeaveRequestUseCase` et `RejectLeaveRequestUseCase` pour créer, approuver et rejeter une demande de congé. Nous utilisons les dépendances appropriées, telles que les repositories (`EmployeeRepository` et `LeaveRequestRepository`) et l'adaptateur de notification par e-mail (`EmailNotificationAdapter`), pour interagir avec les données et les services externes nécessaires.

## Fichier Employee.php

```php
<?php

namespace Domain\Entities;

class Employee
{
    private $id;
    private $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    // Getters and setters for id and name
    // ...
}
```

## Fichier LeaveRequest.php

```php
<?php

namespace Domain\Entities;

class LeaveRequest
{
    private $id;
    private $employeeId;
    private $startDate;
    private $endDate;
    private $status;

    public function __construct($id, $employeeId, $startDate, $endDate)
    {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = 'Pending';
    }

    // Getters and setters for id, employeeId, startDate, endDate, and status
    // ...
}
```

## Fichier EmployeeRepositoryPort.php

```php
<?php

namespace Domain\Ports\Repositories;

use Domain\Entities\Employee;

interface EmployeeRepositoryPort
{
    public function findById($id): ?Employee;
    public function save(Employee $employee): void;
}
```

## Fichier LeaveRequestRepositoryPort.php

```php
<?php

namespace Domain\Ports\Repositories;

use Domain\Entities\LeaveRequest;

interface LeaveRequestRepositoryPort
{
    public function findById($id): ?LeaveRequest;
    public function save(LeaveRequest $leaveRequest): void;
}
```

## Fichier EmailNotificationPort.php

```php
<?php

namespace Domain\Ports\Services;

interface EmailNotificationPort
{
    public function sendNotification($to, $subject, $message): void;
}
```

## Fichier CreateLeaveRequestUseCase.php

```php
<?php

namespace Domain\UseCases;

use Domain\Entities\LeaveRequest;
use Domain\Ports\Repositories\EmployeeRepositoryPort;
use Domain\Ports\Repositories\LeaveRequestRepositoryPort;
use Domain\Ports\Services\EmailNotificationPort;

class CreateLeaveRequestUseCase
{
    private $employeeRepository;
    private $leaveRequestRepository;
    private $emailNotification;

    public function __construct(
        EmployeeRepositoryPort $employeeRepository,
        LeaveRequestRepositoryPort $leaveRequestRepository,
        EmailNotificationPort $emailNotification
    ) {
        $this->employeeRepository = $employeeRepository;
        $this->leaveRequestRepository = $leaveRequestRepository;
        $this->emailNotification = $emailNotification;
    }

    public function execute($employeeId, $startDate, $endDate, $leaveType): void
    {
        // Retrieve employee from repository
        $employee = $this->employeeRepository->findById($employeeId);

        if (!$employee) {
            throw new \Exception("Employee not found");
        }

        // Create leave request
        $leaveRequest = new LeaveRequest(
            uniqid(),
            $employeeId,
            $startDate,
            $endDate,
            $leaveType
        );

        // Save leave request
        $this->leaveRequestRepository->save($leaveRequest);

        // Send notification
        $message = "Leave request created for employee: " . $employee->getName();
        $this->emailNotification->sendNotification($employee->getEmail(), "Leave Request Created", $message);
    }
}
```

## Fichier ApproveLeaveRequestUseCase.php

```php
<?php

namespace Domain\UseCases;

use Domain\Entities\

LeaveRequest;
use Domain\Ports\Repositories\LeaveRequestRepositoryPort;
use Domain\Ports\Services\EmailNotificationPort;

class ApproveLeaveRequestUseCase
{
    private $leaveRequestRepository;
    private $emailNotification;

    public function __construct(
        LeaveRequestRepositoryPort $leaveRequestRepository,
        EmailNotificationPort $emailNotification
    ) {
        $this->leaveRequestRepository = $leaveRequestRepository;
        $this->emailNotification = $emailNotification;
    }

    public function execute($leaveRequestId): void
    {
        // Retrieve leave request from repository
        $leaveRequest = $this->leaveRequestRepository->findById($leaveRequestId);

        if (!$leaveRequest) {
            throw new \Exception("Leave request not found");
        }

        // Update leave request status to 'Approved'
        $leaveRequest->setStatus('Approved');

        // Save leave request
        $this->leaveRequestRepository->save($leaveRequest);

        // Send notification
        $message = "Leave request approved for employee: " . $leaveRequest->getEmployeeId();
        $this->emailNotification->sendNotification($leaveRequest->getEmail(), "Leave Request Approved", $message);
    }
}
```

## Fichier RejectLeaveRequestUseCase.php

```php
<?php

namespace Domain\UseCases;

use Domain\Entities\LeaveRequest;
use Domain\Ports\Repositories\LeaveRequestRepositoryPort;
use Domain\Ports\Services\EmailNotificationPort;

class RejectLeaveRequestUseCase
{
    private $leaveRequestRepository;
    private $emailNotification;

    public function __construct(
        LeaveRequestRepositoryPort $leaveRequestRepository,
        EmailNotificationPort $emailNotification
    ) {
        $this->leaveRequestRepository = $leaveRequestRepository;
        $this->emailNotification = $emailNotification;
    }

    public function execute($leaveRequestId): void
    {
        // Retrieve leave request from repository
        $leaveRequest = $this->leaveRequestRepository->findById($leaveRequestId);

        if (!$leaveRequest) {
            throw new \Exception("Leave request not found");
        }

        // Update leave request status to 'Rejected'
        $leaveRequest->setStatus('Rejected');

        // Save leave request
        $this->leaveRequestRepository->save($leaveRequest);

        // Send notification
        $message = "Leave request rejected for employee: " . $leaveRequest->getEmployeeId();
        $this->emailNotification->sendNotification($leaveRequest->getEmail(), "Leave Request Rejected", $message);
    }
}
```

## Fichier DatabaseEmployeeRepository.php

```php
<?php

namespace Infrastructure\Adapters\Repository;

use Domain\Entities\Employee;
use Domain\Ports\Repositories\EmployeeRepositoryPort;

class DatabaseEmployeeRepository implements EmployeeRepositoryPort
{
    private $employees = [];

    public function findById($id): ?Employee
    {
        // Retrieve employee from database or any data source
        return $this->employees[$id] ?? null;
    }

    public function save(Employee $employee): void
    {
        // Save employee to the database or any data source
        $this->employees[$employee->getId()] = $employee;
    }
}
```

## Fichier DatabaseLeaveRequestRepository.php

```php
<?php

namespace Infrastructure\Adapters\Repository;

use Domain\Entities\LeaveRequest;
use Domain\Ports\Repositories\LeaveRequestRepositoryPort;

class DatabaseLeaveRequestRepository implements LeaveRequestRepositoryPort
{
    private $leaveRequests = [];

    public function findById($id): ?LeaveRequest
    {
        // Retrieve leave request from database or any data source
        return $this->leaveRequests[$id] ?? null;
    }

    public function save(LeaveRequest $leaveRequest): void
   

 {
        // Save leave request to the database or any data source
        $this->leaveRequests[$leaveRequest->getId()] = $leaveRequest;
    }
}
```

## Fichier EmailNotificationAdapter.php

```php
<?php

namespace Infrastructure\Adapters\Services;

use Domain\Ports\Services\EmailNotificationPort;

class EmailNotificationAdapter implements EmailNotificationPort
{
    public function sendNotification($to, $subject, $message): void
    {
        // Send notification via email using an external service or library
        // Example implementation using the PHP mail() function
        mail($to, $subject, $message);
    }
}
```

