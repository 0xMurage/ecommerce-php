<?php

class RegistrationTest extends TestCase
{


    public function testShouldRegisterUser()
    {
        $this->post('/register', ['first_name' => 'Morry',
            'email' => 'morry@kenya.com', 'password' => 'demo1P@ssword'])
            ->seeStatusCode(201);
    }

    public function testShouldErrWithInvalidData()
    {
        $this->post('/register', ['first_name' => 'Morry',
            'email' => 'morrkenya.com', 'password' => 'passw'])
            ->seeStatusCode(422)
            ->seeJsonStructure(['email', 'password']);
    }

    public function testShouldErrIfUserExist()
    {
        $this->post('/register', ['first_name' => 'Morry',
            'email' => 'morry@kenya.com', 'password' => 'demo1P@ssword'])
            ->seeStatusCode(422)
            ->seeJson(['email' => ['The email has already been taken.']]);
    }


    public function testRegisteredUserHasCustomerRoleOnly()
    {
        $email = 'mary3@kenya.com';
        $this->post('/register', ['first_name' => 'Marry',
            'email' => $email, 'password' => 'demo1P@ssword'])
            ->seeStatusCode(201);

        $users = \App\Models\User::with('roles')->where('email', $email)->get();

        assert($users->first()->roles->count() == 1);
    }

    public function testRegisteredUserPasswordIsSecured()
    {
        $email = 'day3@kenya.com';
        $pass = 'demo1P@ssword';
        $this->post('/register', ['first_name' => 'Darry',
            'email' => $email, 'password' => $pass])
            ->seeStatusCode(201);

        $users = \App\Models\User::where('email', $email)->get();

        assert(\Illuminate\Support\Facades\Hash::check($pass, $users->first()->password));
    }
}
