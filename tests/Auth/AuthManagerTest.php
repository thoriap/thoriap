<?php

require '../../bootstrap/application.php';

class AuthManagerTest extends PHPUnit_Framework_TestCase {

    private function userLogin()
    {
        $parameters = array(
            'username' => 'admin',
            'password' => 'admin',
        );

        return Auth::authenticate($parameters);
    }

    private function userLoginError()
    {
        $parameters = array(
            'username' => 'phpunit',
            'password' => 'phpunit',
        );

        return Auth::authenticate($parameters);
    }

    public function testGuest()
    {
        $this->assertTrue(Auth::guest());
    }

    public function testCheck()
    {
        $this->assertFalse(Auth::check());
    }

    public function testError()
    {
        $this->assertFalse($this->userLoginError());
    }

    public function testLogin()
    {
        $this->assertTrue($this->userLogin());

        $this->assertTrue(Auth::check());

        $this->assertFalse(Auth::guest());

        $this->assertSame('admin', Auth::user()->username);
    }

    public function testAdministrator()
    {
        $this->assertTrue($this->userLogin());

        $this->assertTrue(Auth::hasAccess('administrator'));
    }

    public function testLogout()
    {

        $this->assertTrue(Auth::guest());

        $this->assertTrue($this->userLogin());

        $this->assertFalse(Auth::guest());

        $this->assertTrue(Auth::check());

        Auth::logout();

        $this->assertFalse(Auth::check());

    }

}