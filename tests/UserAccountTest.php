<?php

use App\Models\Role;
use App\Models\User;

class UserAccountTest extends TestCase
{
    public function testShouldCreateUser()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();


        $role = Role::query()->orderByDesc('created_at')->first();

        $newAccDetails = ['first_name' => 'Morry', 'email' => 'john@rwanda.com', 'role_id' => $role->id];
        $this->actingAs($impersonationUser)->post('/users/new', $newAccDetails)
            ->seeStatusCode(201)
            ->seeJson(['message' => 'Account created successfully.']);

        $this->assertTrue($this->response['user']['email'] == $newAccDetails['email']);

    }

    public function testShouldErrorOnCreateWithInvalidData()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        $newAccDetails = ['first_name' => 'Jome', 'email' => $impersonationUser->email,
            'role_id' => 304994];

        $this->actingAs($impersonationUser)->post('/users/new', $newAccDetails)
            ->seeStatusCode(422)
            ->seeJson(['email' => ['The email has already been taken.'],
                'role_id' => ['Role assigned does not exists']]);
    }

    public function testShouldFetchUsers()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        $this->actingAs($impersonationUser)->get('/users/show')
            ->seeStatusCode(200)
            ->seeJson(['message' => 'All user accounts'])
            ->seeJsonStructure(['users' => [0 => ['id', 'first_name']]]);
        $this->assertCount(User::count(), $this->response['users']);
    }

    public function testShouldUpdateUser()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        //get a random account to update
        $account = User::orderBy('first_name')->first();

        //get them a new role
        $role = Role::query()->orderByDesc('created_at')->first();

        $updateDetails = ['first_name' => 'Jeremiah',
            'email' => 'jeremiah@rwanda.com', 'role_id' => $role->id];

        $this->actingAs($impersonationUser)->put('/users/update/' . $account->id, $updateDetails)
            ->seeStatusCode(201)
            ->seeJson(['message' => 'User details updated successfully.']);

        $this->assertTrue($this->response['user']['first_name'] == $updateDetails['first_name']);
        $this->assertTrue($this->response['user']['email'] == $updateDetails['email']);
    }

    public function testShouldErrorOnUpdateWithInvalidData()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        //get a random account to update
        $account = User::where('id', '!=', $impersonationUser->id)->first();

        $this->actingAs($impersonationUser)->put('/users/update/' . $account->id,
            ['email' => $impersonationUser->email, 'role_id' => 3404433])
            ->seeStatusCode(422)
            ->seeJson(['email' => ['The email has already been taken.'],
                'role_id' => ['Role assigned does not exists']]);
    }

    public function testShouldDeleteUser()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        //get a random account to delete
        $account = User::orderBy('created_at')->first();

        $this->actingAs($impersonationUser)->delete('/users/delete/' . $account->id)
            ->seeStatusCode(201)
            ->seeJson(['message' => 'User account deleted successfully.']);

        $this->assertEmpty(User::find($account->id));
    }

    public function testShouldErrorOnInvalidUserUpdateDetails()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();


        $this->actingAs($impersonationUser)->delete('/users/delete/90322')
            ->seeStatusCode(404)
            ->seeJson(['message' => 'Resource not found']);

    }


    public function testShouldErrorWithoutAuth()
    {
        $this->get('/users/show')
            ->seeStatusCode(401);
    }

    public function testShouldErrorIfNotAdminOnFetch()
    {
        $impersonationUser = User::whereDoesntHave('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        $this->actingAs($impersonationUser)->get('/users/show')
            ->seeStatusCode(403);
    }

    public function testShouldErrorIfNotAdminOnCreate()
    {
        $impersonationUser = User::whereDoesntHave('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        $newAccDetails = ['first_name' => 'Jome', 'email' => 'demo23@rwanda.com', 'role_id' => 1];

        $this->actingAs($impersonationUser)->post('/users/new', $newAccDetails)
            ->seeStatusCode(403);
    }

    public function testShouldErrorIfNotAdminOnUpdate()
    {
        $impersonationUser = User::whereDoesntHave('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();


        $acc = ['first_name' => 'Davis Jnr.'];

        $this->actingAs($impersonationUser)->put('/users/update/' . $impersonationUser->id, $acc)
            ->seeStatusCode(403);
    }

    public function testShouldErrorIfNotAdminOnDelete()
    {
        $impersonationUser = User::whereDoesntHave('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        $this->actingAs($impersonationUser)->delete('/users/delete/' . $impersonationUser->id)
            ->seeStatusCode(403);
    }

}
