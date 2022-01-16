<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RoleManagementTest extends TestCase
{
    public function testShouldCreateRole()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();


        $permissions = Permission::limit(4)->get();

        $newRole = ['name' => 'Test role x', 'description' => 'test',
            'permissions' => $permissions->map(function ($p) {
                return ['id' => $p->id];
            })->toArray()
        ];

        $this->actingAs($impersonationUser)->post('/roles/new', $newRole)
            ->seeStatusCode(201)
            ->seeJson(['message' => 'Role created successfully.']);

        $this->assertTrue($this->response['role']['name'] == $newRole['name']);

    }

    public function testShouldErrorOnCreateWithInvalidData()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        $newRole = ['name' => 'admin', 'permissions' => [['id' => 2838]]];

        $this->actingAs($impersonationUser)->post('/roles/new', $newRole)
            ->seeStatusCode(422)
            ->seeJson(['name' => ['Role with similar name already exist'],
                'permissions.0.id' => ['Invalid permission provided']]);
    }

    public function testShouldFetchRoles()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        $this->actingAs($impersonationUser)->get('/roles/show')
            ->seeStatusCode(200)
            ->seeJson(['message' => 'All roles'])
            ->seeJsonStructure(['roles' => [0 => ['id', 'name']]]);

        $this->assertCount(Role::count(), $this->response['roles']);
    }

    public function testShouldUpdateRole()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        //get a random role to update
        $role = Role::where('name', '!=', 'admin')->first();

        $updateDetails = ['name' => 'demo', 'permissions' => [['id' => 2]]];


        $this->actingAs($impersonationUser)->put('/roles/update/' . $role->id, $updateDetails)
            ->seeStatusCode(200)
            ->seeJson(['message' => 'Role updated successfully.']);

        $this->assertTrue($this->response['role']['name'] == $updateDetails['name']);
        $this->assertTrue($this->response['role']['permissions'][0]['id'] == 2);
    }

    public function testShouldErrorOnUpdateWithInvalidData()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        //get a random role to update

        $this->actingAs($impersonationUser)->put('/roles/update/3',
            ['name' => 'admin', 'permissions' => [['id' => 40393]]])
            ->seeStatusCode(422)
            ->seeJson(['name' => ['Role with similar name already exist']]);
    }

    public function testShouldDeleteUser()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();

        //get a random account to delete
        $role = Role::doesntHave('users')->first();

        $this->actingAs($impersonationUser)->delete('/roles/delete/' . $role->id)
            ->seeStatusCode(200)
            ->seeJson(['message' => 'Role deleted successfully.']);

        $this->assertEmpty(Role::find($role->id));
    }

    public function testShouldErrorIfRoleNotFoundOnDelete()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();


        $this->actingAs($impersonationUser)->delete('/roles/delete/90322')
            ->seeStatusCode(404)
            ->seeJson(['message' => 'Resource not found']);

    }


    public function testShouldErrorWithoutAuth()
    {
        $this->get('/roles/show')
            ->seeStatusCode(401);

        $this->post('/roles/new', ['name' => 'test'])
            ->seeStatusCode(401);

        $this->put('/roles/update/2', ['name' => 'test'])
            ->seeStatusCode(401);

        $this->delete('/roles/delete/2')
            ->seeStatusCode(401);
    }

}
