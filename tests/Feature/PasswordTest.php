<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    public function test_it_hashes_password_correctly(): void
    {
        $password = new \App\Domains\Identity\ValueObjects\Password('my_Secure_p4ssword');
        $hashed = $password->hashed();

        $this->assertNotEquals('my_Secure_p4ssword', $hashed);
        $this->assertTrue(password_verify('my_Secure_p4ssword', $hashed));
    }
}
