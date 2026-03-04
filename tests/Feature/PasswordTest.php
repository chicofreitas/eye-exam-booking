<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Domains\Identity\DTOs\CreateUserDTO;
use App\Domains\Identity\Services\IdentityService;
use App\Domains\Identity\Factories\ProfileCreatorFactory;
use App\Domains\Identity\Services\Creators\DoctorProfileCreator;
use App\Domains\Identity\Services\Creators\PatientProfileCreator;
use App\Domains\Identity\ValueObjects\Password;
use InvalidArgumentException;


class PasswordTest extends TestCase
{
    use RefreshDatabase;

    private IdentityService $service;
    private ProfileCreatorFactory $factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = new ProfileCreatorFactory(
            new DoctorProfileCreator(), 
            new PatientProfileCreator()
        );
        $this->service = new IdentityService($this->factory);
    }

    public function test_it_hashes_password_correctly(): void
    {
        $password = new \App\Domains\Identity\ValueObjects\Password('my_Secure_p4ssword');
        $hashed = $password->hashed();

        $this->assertNotEquals('my_Secure_p4ssword', $hashed);
        $this->assertTrue(password_verify('my_Secure_p4ssword', $hashed));
    }

    public function test_it_accepts_registration_when_passwords_match(): void
    {
        // 1. Prepare data where passwords match
        $data = [
            'name' => 'John Doe',
            'email' => 'valid@example.com',
            'password' => 'SecurePass123',
            'confirm_password' => 'SecurePass123',
            'role' => 'patient',
            'profile_info' => ['blood_type' => 'A+']
        ];

        // 2. Act: Create DTO and Register
        $dto = CreateUserDTO::fromRequest($data);
        $user = $this->service->register($dto);

        // 3. Assert: User exists in database
        $this->assertDatabaseHas('users', [
            'email' => 'valid@example.com',
        ]);
        
        $this->assertTrue(password_verify('SecurePass123', $user->password));
    }

    public function test_it_throws_exception_when_passwords_do_not_match(): void
    {
        // 1. Prepare data with mismatch
        $data = [
            'name' => 'Jane Doe',
            'email' => 'mismatch@example.com',
            'password' => 'SecurePass123',
            'confirm_password' => 'DifferentPass456',
            'role' => 'patient',
            'profile_info' => []
        ];

        // 2. Assert: The DTO should throw an exception before reaching the service
        $this->expectExceptionMessage("Passwords do not match.");

        // 3. Act
        CreateUserDTO::fromRequest($data);
    }

    public function test_can_create_valid_password_with_special_characters(): void
    {
        $password = new Password('ValidP@ss123');
        $this->assertInstanceOf(Password::class, $password);
        $this->assertEquals('ValidP@ss123', $password->value);
    }

    public function test_can_create_valid_password_with_minimum_length(): void
    {
        $password = new Password('Pass1234');
        $this->assertEquals('Pass1234', $password->value);
    }

    /**
     * Validation tests - Length
     */
    public function test_throws_exception_if_password_is_too_short(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters.');
        new Password('short');
    }

    public function test_throws_exception_if_password_is_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters.');
        new Password('');
    }

    public function test_throws_exception_if_password_has_exactly_7_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters.');
        new Password('Pass123');
    }

    /**
     * Validation tests - Uppercase letter requirement
     */
    public function test_throws_exception_if_there_is_no_uppercase_letter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one uppercase letter and one number.');
        new Password('nouppercase1');
    }

    public function test_throws_exception_if_password_is_all_lowercase_with_numbers(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one uppercase letter and one number.');
        new Password('alllowercase1');
    }

    /**
     * Validation tests - Number requirement
     */
    public function test_throws_exception_if_there_is_no_number(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one uppercase letter and one number.');
        new Password('NoNumberPassword');
    }

    public function test_throws_exception_if_password_is_all_letters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one uppercase letter and one number.');
        new Password('NoNumbers');
    }

    /**
     * Equals method tests
     */
    public function test_equals_returns_true_for_same_passwords(): void
    {
        $password1 = new Password('ValidPass123');
        $password2 = new Password('ValidPass123');
        $this->assertTrue($password1->equals($password2));
    }

    public function test_equals_returns_false_for_different_passwords(): void
    {
        $password1 = new Password('ValidPass123');
        $password2 = new Password('DifferentPass456');
        $this->assertFalse($password1->equals($password2));
    }

    /**
     * Hashed method tests
     */
    public function test_hashed_returns_different_value_than_plain(): void
    {
        $plainPassword = 'ValidPass123';
        $password = new Password($plainPassword);
        $hashed = $password->hashed();
        
        $this->assertNotEquals($plainPassword, $hashed);
    }

    public function test_hashed_returns_a_string(): void
    {
        $password = new Password('ValidPass123');
        $hashed = $password->hashed();
        
        $this->assertIsString($hashed);
    }

    public function test_hashed_returns_valid_bcrypt_hash(): void
    {
        $password = new Password('ValidPass123');
        $hashed = $password->hashed();
        
        // Bcrypt hashes are 60 characters long
        $this->assertSame(60, strlen($hashed));
        
        // Should start with bcrypt identifier
        $this->assertStringStartsWith('$2y$', $hashed);
        
        // Should verify correctly against the original password
        $this->assertTrue(password_verify('ValidPass123', $hashed));
    }

    /**
     * ToString method tests
     */
    public function test_to_string_returns_plain_password(): void
    {
        $plainPassword = 'ValidPass123';
        $password = new Password($plainPassword);
        
        $this->assertEquals($plainPassword, (string) $password);
    }

    public function test_to_string_returns_same_as_value_property(): void
    {
        $password = new Password('ValidPass123');
        
        $this->assertEquals($password->value, (string) $password);
    }

    /**
     * Special cases
     */
    public function test_password_with_multiple_uppercase_letters(): void
    {
        $password = new Password('VALID1Password');
        $this->assertEquals('VALID1Password', $password->value);
    }

    public function test_password_with_multiple_numbers(): void
    {
        $password = new Password('ValidPass12345');
        $this->assertEquals('ValidPass12345', $password->value);
    }

    public function test_password_with_spaces_but_valid(): void
    {
        $password = new Password('Valid Pass1');
        $this->assertEquals('Valid Pass1', $password->value);
    }

    public function test_password_is_readonly(): void
    {
        $password = new Password('ValidPass123');
        
        // Attempting to set a property on a readonly object should fail
        $this->expectException(\Error::class);
        $password->value = 'NewPassword123';
    }
}
