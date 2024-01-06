<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'email', Rule::exists(User::class,'email')],
            'password' => ['required', 'min:8'],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please Validate Data', $validator->errors(),422);
        }

        $user = User::where('email','=',$request->email)->first();
        if (!Hash::check($request['password'], $user->password)){
            return $this->sendError('Incorrect Password','', 422);
        }
        return $this->sendResponse($this->respondWithToken($this->token($user), $user), 'Login Successfully');
    }

    public function token($user){
        return $user->createToken(str()->random(40))->plainTextToken;
    }
}
