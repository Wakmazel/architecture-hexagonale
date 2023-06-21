<?php

namespace Domain\Ports\Repositories;

use Domain\Entities\Employee;

interface EmployeeRepositoryPort
{
    public function findById($id): ?Employee;
    public function save(Employee $employee): void;
}