<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Factories\ProfileCreatorFactory;
use App\Domains\Identity\ValueObjects\ApiToken;
use Illuminate\Validation\ValidationException;
use App\Domains\Identity\DTOs\CreateUserDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class IdentityService 
{
  private ProfileCreatorFactory $factory;

  public function __construct(ProfileCreatorFactory $factory) 
  {
    $this->factory = $factory;
  }

  public function register(CreateUserDTO $dto): User 
  {
    return DB::transaction(function () use ($dto) {
      // 1. Create Base User
      $user = User::create($dto->toArray());

      // 2. Use Factory to get the strategy
      $creator = $this->factory->make($dto->role);

      // 3. Execute the strategy
      $creator->create($user->id, $dto->profileData);

      return $user;
    });
  }

  public function login(string $email, string $password): ?ApiToken 
  {
    if (!Auth::attempt(['email' => $email, 'password' => $password])) {
      throw ValidationException::withMessages([
        'email' => ['The provided credentials do not match our records.'],
      ]);
    }

    $apiToken = ApiToken::fromString(Auth::user()->api_token ?? Auth::user()->createToken('auth_token')->plainTextToken);

    return $apiToken;
  }
}