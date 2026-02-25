<?php

namespace App\Http\Controllers\Api\Identity;

use App\Domains\Identity\DTOs\CreateUserDTO;
use App\Domains\Identity\Services\IdentityService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
  public function __construct(private IdentityService $identityService) {}

  public function register(Request $request)
  {
    $this->identityService->register(CreateUserDTO::fromRequest($request->all()));
  }
}