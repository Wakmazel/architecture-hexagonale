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