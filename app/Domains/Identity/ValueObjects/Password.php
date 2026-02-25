<?php

namespace App\Domains\Authentication\ValueObjects;

use InvalidArgumentException;
use Illuminate\Support\Facades\Hash;

readonly class Password
{
  public function __construct(
    public string $value 
  ) {
    $this->validate($value);
  }

  private function validate(string $value): void
  {
    // Centralize your business rules here
    if (strlen($value) < 8) {
      throw new InvalidArgumentException("Password must be at least 8 characters.");
    }

    if (!preg_match('/[A-Z]/', $value) || !preg_match('/[0-9]/', $value)) {
      throw new InvalidArgumentException("Password must contain at least one uppercase letter and one number.");
    }
  }

  /**
   * Returns the hashed version of the password for database storage.
   */
  public function hashed(): string
  {
    return Hash::make($this->value);
  }

  public function __toString(): string
  {
    return $this->value;
  }
}