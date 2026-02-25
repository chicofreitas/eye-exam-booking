<?php

namespace App\Domains\Authentication\ValueObjects;

use InvalidArgumentException;

class Email {
  public function __construct(public readonly string $value) {
    $this->validate($value);
  }

  private function validate(string $value): void
  {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      throw new InvalidArgumentException("Invalid email format: {$value}");
    }
  }

  public static function fromString(string $email): self {
    return new self($email);
  }

  public function equals(Email $other): bool {
    return $this->value === $other->value;
  }

  public function __toString(): string {
    return $this->value;
  }
}