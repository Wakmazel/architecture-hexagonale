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