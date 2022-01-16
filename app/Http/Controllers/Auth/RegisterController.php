<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        #validate the request
        $validated = $this->validate($request, [
            'first_name' => ['required', 'min:2', 'max:200'],
            'last_name' => ['sometimes', 'max:200'],
            'phone_number' => ['sometimes', 'digits_between:10,12'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'max:25', Password::min(8)->mixedCase()->numbers()],
        ], ['phone_number.digits_between' =>
            'The phone number format should be either 2547XXXXXXXX or 07XXXXXXXX']);

        //hash the password
        $validated['password'] = Hash::make($validated['password']);

        DB::transaction(function () use ($validated) {
            //save the user
            $user = User::create($validated);

            //assign user customer role
            $user->roles()->save(Role::where('name', 'Customer')->firstOrFail());
        });

        return response()
            ->json(['message' => 'Account created successfully. You can now login'], 201);
    }
}
