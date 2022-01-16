<?php

class LoginTest extends TestCase
{
    public function testShouldLoginUser()
    {
        $user = \App\Models\User::first();
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->seeStatusCode(200);
    }

    public function testShouldErrWithInvalidData()
    {
        $this->post('/login', ['email' => 'kenya.com'])
            ->seeStatusCode(422)
            ->seeJsonStructure(['email', 'password']);
    }

    public function testShouldErrIfCredentialInvalid()
    {
        $user = \App\Models\User::first();
        $this->post('/login', ['email' => $user->email . 'demo', 'password' => 'password122'])
            ->seeStatusCode(401)
            ->seeJson(['message' => 'Invalid email/password combination.']);
    }
}
