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