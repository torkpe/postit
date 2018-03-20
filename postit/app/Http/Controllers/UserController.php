<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ConflictException;
use App\Models\User;

class UserController extends Controller
{
  public function createUser(Request $request)
  {
    $this->validate($request, [
      'username' => 'required',
      'email' => 'required',
  ]);
    $checkIfUsernameExists = User::where('username', $request->input('username'))->get();
    if (count($checkIfUsernameExists) > 0) {
      throw new ConflictException('Looks like somebody else registered with this username');
    }
    $checkIfEmailExists = User::where('email', $request->input('email'))->get();
    if (count($checkIfEmailExists) > 0) {
      throw new ConflictException('Looks like somebody else registered with this email');
    }

    $createdUser = User::create($request->all());
    $message["response"]["message"] = "Account created successfully";
    $message["response"]["createdUser"] = $createdUser;
    return response()->json($message, 201);
  }
}