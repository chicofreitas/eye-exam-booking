<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function test_it_throws_exception_if_password_is_too_short(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $password = new \App\Domains\Identity\ValueObjects\Password('short');
    }

    public function test_it_throws_exception_if_there_is_no_at_least_one_uppercase_letter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $password = new \App\Domains\Identity\ValueObjects\Password('nouppercase1');
    }

    public function test_it_throws_exception_if_there_is_no_at_least_one_number(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $password = new \App\Domains\Identity\ValueObjects\Password('NoNumberPassword');
    }
}
