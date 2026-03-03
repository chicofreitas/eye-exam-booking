<?php

namespace App\Domains\Identity\DTOs;

use App\Domains\Identity\ValueObjects\Password;

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
    $password = new Password($data['password'] ?? '');
    $confirm  = new Password($data['confirm_password'] ?? '');

    if (!$password->equals($confirm)) {
      throw new \InvalidArgumentException("Passwords do not match.");
    }

    return new self(
      name: $data['name'],
      email: $data['email'],
      password: $password->hashed(),
      role: $data['role'],
      profileData: $data['profile_info'] ?? []
    );
  }
}