<?php

namespace Tests\Unit;

use App\Domains\Identity\ValueObjects\Password;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    /**
     * Valid password creation tests
     */
    public function test_can_create_valid_password(): void
    {
        $password = new Password('ValidPass123');
        $this->assertInstanceOf(Password::class, $password);
        $this->assertEquals('ValidPass123', $password->value);
    }

    
}
