<?php

namespace App\Domains\Authentication\ValueObjects;

use InvalidArgumentException;

readonly class Token
{
  public function __construct(
    public readonly string $value
  ) {
    $this->validate($value);
  }

  public static function fromString(string $value): Token
  {
    return new Token($value);
  }
  
  private function validate(string $value): void
  {
    // Tokens shouldn't be empty or suspiciously short
    if (empty(trim($value))) {
      throw new InvalidArgumentException("Token cannot be empty.");
    }
  }

  /**
   * Helper to return the full string needed for the Authorization header.
   */
  public function bearer(): string
  {
    return "Bearer {$this->value}";
  }

  public function __toString(): string
  {
    return $this->value;
  }
}