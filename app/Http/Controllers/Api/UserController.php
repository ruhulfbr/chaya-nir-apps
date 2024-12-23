<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAuthenticatedUser(): \Illuminate\Http\JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            return response()->json([
                'id'            => $user->id,
                'authenticated' => true,
                'user'          => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
            ]);
        }

        return response()->json([
            'authenticated' => false,
            'message'       => 'User is not authenticated.',
        ]);
    }
}
