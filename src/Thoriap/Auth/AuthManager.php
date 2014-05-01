<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\Auth;

use stdClass;
use Thoriap\Alias\Model\Users;
use Thoriap\Alias\Model\Groups;
use Thoriap\Session\Store as Session;

class AuthManager {

    /**
     * Session'ı tutar.
     *
     * @var Session
     */
    private $session;

    /**
     * Kullanıcıları tutar.
     *
     * @var array
     */
    private $users;

    /**
     * Gizlenecek kullanıcı bilgileri.
     *
     * @var array
     */
    private $hidden = array('password');

    /**
     * Başlangıç işlemleri.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Herhangi bir yetki kontrolü yapar.
     *
     * @param array $access
     * @param null $userId
     * @return bool
     */
    public function hasOneAccess(array $access, $userId = null)
    {
        foreach($access as $permission)
        {
            if ( $this->hasAccess($permission, $userId) === true )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Yetki kontrolü yapar.
     *
     * @param $access
     * @param null $userId
     * @return bool
     */
    public function hasAccess($access, $userId = null) {


        if ( is_null($userId) )
        {

            if ( !$this->check() )
            {
                return false;
            }

            $userId = $this->user()->user_id;

        }

        if (!isset($this->users[$userId]))
        {

            if ( $user = Users::getOne($userId) )
            {
                $userAccess = unserialize($user->user_access);


                /*
                if ( $user->user_group )
                {
                    if ( $group = Groups::getOne($user->user_group) )
                    {
                        $userAccess = array_merge($userAccess, unserialize($group->group_access));
                    }
                }
                */

                $this->users[$userId] = $userAccess;

            }
            else
            {
                return false;
            }

        }

        if ( isset($this->users[$userId]) )
        {
            $accessArray = $this->users[$userId];

            if ( array_key_exists('administrator', $accessArray) )
            {
                return true;
            }
            else
            {
                return array_get($accessArray, $access);
            }
        }

    }

    /**
     * Giriş yapılmışsa olumlu döner.
     *
     * @return bool
     */
    public function check()
    {

        $user = $this->session->get('administrator.user');

        if ( $user instanceof stdClass )
        {
            return true;
        }

        return false;
    }

    /**
     * Giriş yapılmamışsa olumlu döner.
     *
     * @return bool
     */
    public function guest()
    {

        return $this->check() ? false : true;

    }

    /**
     * Giriş yapan kullanıcı bilgilerini dönderir.
     *
     * @return stdClass
     */
    public function user()
    {

        return $this->session->get('administrator.user', new stdClass());

    }

    /**
     * Parametreler ile giriş yapar.
     *
     * @param array $credentials
     * @param bool $reminder
     * @return bool
     */
    public function authenticate(array $credentials, $reminder = false)
    {

        $username = $credentials['username'];

        $password = sha1($credentials['password']);

        if ( $getUser = Users::exists($username, $password) )
        {
            return $this->login($getUser);
        }

        return false;

    }

    /**
     * Oturum açar.
     *
     * @param stdClass $user
     * @return bool
     */
    public function login(stdClass $user)
    {
        if ( $user )
        {

            foreach($this->hidden as $hidden)
            {
                if ( isset($user->{$hidden}) )
                {
                    unset($user->{$hidden});
                }
            }

            $this->session->set('administrator', array('user' => $user, 'messages' => array()));

            return true;

        }
        return false;
    }

    /**
     * Oturumu kapatır.
     *
     * @return void
     */
    public function logout()
    {
        $this->session->flush('administrator');
    }

}