<?php

namespace Domain\Ports\Services;

interface EmailNotificationPort
{
    public function sendNotification($to, $subject, $message): void;
}