<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Factories\ProfileCreatorFactory;
use App\Domains\Identity\DTOs\CreateUserDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RegisterService 
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
      $user = User::create([
        'name'     => $dto->name,
        'email'    => $dto->email,
        'password' => Hash::make($dto->password),
        'role'     => $dto->role
      ]);

      // 2. Use Factory to get the strategy
      $creator = $this->factory->make($dto->role);

      // 3. Execute the strategy
      $creator->create($user->id, $dto->profileData);

      return $user;
    });
  }
}