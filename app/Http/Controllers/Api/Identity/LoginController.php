<?php

namespace App\Http\Controllers\Api\Identity;

use App\Domains\Identity\Services\IdentityService;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
  public function __construct(private IdentityService $identityService){}

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    try {
      $apiToken = $this->identityService->login($credentials['email'], $credentials['password']);
      return response()->json(['token' => $apiToken->value], 200);
    } catch (ValidationException $e) {
      return response()->json(['errors' => $e->errors()], 422);
    }
  }
}