<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserAccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return User::with('roles')->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        #validate the request
        $validated = $this->validate($request, [
            'first_name' => ['required', 'min:2', 'max:200'],
            'last_name' => ['sometimes', 'max:200'],
            'phone_number' => ['sometimes', 'digits_between:10,12'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'role_id' => ['required', 'exists:roles,id']
        ],
            ['phone_number.digits_between' => 'Format should be either 2547XXXXXXXX or 07XXXXXXXX',
                'role_id.required' => 'User needs to be assigned a role',
                'role_id.exists' => 'Role assigned does not exists']);


        //hash a random password
        $validated['password'] = Hash::make(Str::random(8));;

        DB::beginTransaction();
        //save the user details
        unset($validated['role_id']);

        $validated['user_id'] = Auth::id(); #attach the author
        $user = User::create($validated);

        //assign user the role
        $user->roles()->attach(['id' => $request->only('role_id')],
            ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        DB::commit();

        /**
         * TODO: emit an even to email the user so they can set a new password
         * The url should be a signed url
         * /api/v1/users/reset_password/<token>
         *
         */

        //refresh the roles
        $user->load('roles');
        return response()
            ->json(['message' => 'Account created successfully.',
                'user' => $user], 201);

    }


    public function update(Request $request, $id)
    {
        //retrieve the user
        $user = User::findOrFail($id);

        // Validate the request
        $validated = $this->validate($request, [
            'first_name' => ['sometimes', 'min:2', 'max:200'],
            'last_name' => ['sometimes', 'max:200'],
            'phone_number' => ['sometimes', 'digits_between:10,12'],
            'email' => ['sometimes', 'email', 'max:100',
                Rule::unique('users', 'email')->ignore($user)],
            'role_id' => ['sometimes', 'exists:roles,id']
        ],
            ['phone_number.digits_between' => 'Format should be either 2547XXXXXXXX or 07XXXXXXXX',
                'role_id.exists' => 'Role assigned does not exists']);

        DB::beginTransaction();

        unset($validated['role_id']);

        $validated['user_id'] = Auth::id(); #attach the editor

        #do the actual update
        $user->update($validated);

        if ($request->has('role_id')) {
            //if the roles have been update, sync
            $user->roles()->sync(['id' => $request->only('role_id')],
                ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }

        DB::commit();


        //reload the roles
        $user->load('roles');
        return response()
            ->json(['message' => 'User details updated successfully.',
                'user' => $user], 201);
    }

}
