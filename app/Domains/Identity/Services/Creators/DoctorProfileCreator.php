<?php

namespace App\Domains\Identity\Services\Creators;

use App\Domains\Identity\Interfaces\ProfileCreatorInterface;
//use Illuminate\Support\Facades\DB;
use App\Models\Doctor;

class DoctorProfileCreator implements ProfileCreatorInterface 
{
  public function create(int $userId, array $data): void 
  {
    // Imagine $this->db is a PDO instance or an ORM
    Doctor::create([
      'user_id' => $userId,
      'crm_license' => $data['license'] ?? null,
    ]);
  }
}