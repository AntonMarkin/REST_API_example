<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends APIController
{
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id_types_id' => 'required|max:2|min:1',
            'login_id' => 'required|min:2|max:20|string',
            'password' => 'required|max:20|min:5',
        ]);
        if ($validator->fails()) {
            return $this->sendError('validation error.', $validator->errors()->first());
        }
        $input = $validator->validated();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $success['token'] = $user->createToken('user')->accessToken;
        $success['user'] = $user;
        return $this->sendResponse($success, 'sign up is successfully.');
    }

    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id' => 'required|min:2|max:20',
            'password' => 'required|max:20|min:6',
            'remember' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation error.', $validator->errors());
        }

        $input = $validator->validated();
        if (Auth::attempt(['login_id' => $input['login_id'], 'password' => $input['password']], $input['remember'])) {
            $success = [
                'token' => Auth::user()->createToken('user')->accessToken,
                'user' => Auth::user(),
            ];
            return $this->sendResponse($success, 'sign in is successfully.');
        }

        return $this->sendError('credentials is entered incorrectly');
    }

    public function info()
    {
        if (Auth::hasUser()) {
            $user = Auth::user();
            $success = [
                'id_type' => $user['id_types_id'],
                'login_id' => $user['login_id'],
            ];
            return $this->sendResponse($success, 'user id and id type');
        }
        return $this->sendError('there is no active user');
    }

    public function latency()
    {
        $response = Http::get('ya.ru')->handlerStats();
        return $this->sendResponse(['latency' => round($response['total_time'] * 1000, 3)], 'latency to ya.ru at msec');
    }

    public function logout(Request $request, bool $all)
    {
        if (!$all) {
            Auth::user()->token()->revoke();
        } else {
            Auth::user()->tokens->each(function ($token, $key) {
                $token->delete();
            });
        }
        Auth::logout();

    }
}
