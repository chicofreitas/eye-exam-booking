<?php

namespace App\Domains\Identity\Interfaces;

interface ProfileCreatorInterface 
{
    public function create(int $userId, array $data): void;
}