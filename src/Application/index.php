<?php
use Domain\UseCases\CreateLeaveRequestUseCase;
use Infrastructure\Adapters\Repository\DatabaseEmployeeRepository;
use Infrastructure\Adapters\Repository\DatabaseLeaveRequestRepository;
use Infrastructure\Adapters\Services\EmailNotificationAdapter;
require 'vendor/autoload.php';

$employeeRepository = new DatabaseEmployeeRepository();
$leaveRequestRepository = new DatabaseLeaveRequestRepository();
$emailNotification = new EmailNotificationAdapter();

$createLeaveRequestUseCase = new CreateLeaveRequestUseCase(
    $employeeRepository,
    $leaveRequestRepository,
    $emailNotification
);


// controlleur

// new LeaveRequest()
$createLeaveRequestUseCase->execute(
    12, "2020-08-01","2020-08-01", 3
);


?>