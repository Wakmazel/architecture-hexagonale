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
            $endDate
        );

        // Save leave request
        $this->leaveRequestRepository->save($leaveRequest);

        // Send notification
        $message = "Leave request created for employee: " . $employee->getName();
        $this->emailNotification->sendNotification($employee->getEmail(), "Leave Request Created", $message);
    }
}