<?php


namespace App\ValueObjects;


class UserFilters
{
    private $name;
    
    public function __construct(array $filters = [])
    {
        $this->name = trim((string) $filters['name']);
    }
    
    
    public function getName(): string
    {
        return  $this->name;
    }
}