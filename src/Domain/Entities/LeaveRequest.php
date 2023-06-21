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