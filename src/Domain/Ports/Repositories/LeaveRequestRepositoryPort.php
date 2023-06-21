<?php

namespace Domain\Ports\Repositories;

use Domain\Entities\LeaveRequest;

interface LeaveRequestRepositoryPort
{
    public function findById($id): ?LeaveRequest;
    public function save(LeaveRequest $leaveRequest): void;
}