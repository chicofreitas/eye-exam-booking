<?php

namespace App\Domains\Identity\Services\Creators;

use App\Domains\Identity\Interfaces\ProfileCreatorInterface;
use App\Models\Patient;

class PatientProfileCreator implements ProfileCreatorInterface 
{
    public function create(int $userId, array $data): void 
    {
        Patient::create([
            'user_id' => $userId,
            'document_number' => $data['document_number'] ?? 'not informed',
            'birth_date' => $data['birth_date'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
            'gender' => $data['gender'] ?? 'not informed',
        ]);
    }
}