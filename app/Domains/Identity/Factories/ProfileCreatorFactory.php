<?php

namespace App\Domains\Identity\Factories;

use App\Domains\Identity\Interfaces\ProfileCreatorInterface;
use App\Domains\Identity\Services\Creators\DoctorProfileCreator;
use App\Domains\Identity\Services\Creators\PatientProfileCreator;

class ProfileCreatorFactory 
{
  private array $creators;

  public function __construct(
    DoctorProfileCreator $doctorCreator,
    PatientProfileCreator $patientCreator,
  ) {
    $this->creators = [
      'doctor' => $doctorCreator,
      'patient' => $patientCreator,
    ];
  }

  public function make(string $role): ProfileCreatorInterface 
  {
    if (!isset($this->creators[$role])) {
      throw new \InvalidArgumentException("Unsupported role: {$role}");
    }

    return $this->creators[$role];
  }
}