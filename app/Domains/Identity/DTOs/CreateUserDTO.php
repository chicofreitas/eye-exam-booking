<?php

namespace App\Domains\Identity\DTOs;

readonly class CreateUserDTO
{
  public function __construct(
    public string $name,
    public string $email,
    public string $password,
    public string $role,
    public array $profileData
  ) {}

  public static function fromRequest(array $data): self
  {
    return new self(
      name: $data['name'],
      email: $data['email'],
      password: $data['password'],
      role: $data['role'],
      profileData: $data['profile_info'] ?? []
    );
  }
}