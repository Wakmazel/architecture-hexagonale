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