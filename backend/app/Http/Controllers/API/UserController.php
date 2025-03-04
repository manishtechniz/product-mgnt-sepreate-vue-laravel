<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function update($id): JsonResponse
    {
        $validate = Validator::make(request()->all(), [
            'name'     => 'nullable|max:20',
            'email'    => 'nullable|email|unique:users,email,' . $id,
            'phone'    => 'nullable|unique:users,phone,' . $id,
            'password' => 'nullable|min:5',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validate->errors(),
            ], 422);
        }

        $user = User::findOrFail($id);

        if (request('name')) {
            $user->name = request('name');
        }

        if (request('email')) {
            $user->email = request('email');
        }

        if (request('phone')) {
            $user->phone = request('phone');
        }

        if (request('password')) {
            $user->password = Hash::make(request('password'));
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user'    => $user
        ], 200);
    }
}
