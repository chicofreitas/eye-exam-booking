<?php

namespace App\Http\Controllers\Api\Identity;

use App\Domains\Identity\Services\RegisterService;
use App\Domains\Identity\DTOs\CreateUserDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
  private RegisterService $registerService;

  public function __construct(RegisterService $registerService) 
  {
    $this->registerService = $registerService;
  }

  public function register(Request $request)
  {
    $this->registerService->register(CreateUserDTO::fromRequest($request->all()));
  }
}