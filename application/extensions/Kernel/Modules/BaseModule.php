<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kernel\Modules;

use Session, Auth, Input, Validator, View, Redirect;
use Thoriap\Core\Factory\ExtensionsBase;
use Thoriap\Alias\Models\Languages;

class BaseModule extends ExtensionsBase {

    /**
     * Yönetim paneli ana sayfası.
     *
     * @return mixed
     */
    public function index()
    {

        View::setTitle('Genel Bakış');

        return View::make('index');

    }

    /**
     * Oturum açma sayfası.
     *
     * @return mixed
     */
    public function login()
    {

        $translations = $this->translations('login');

        View::setTranslations($translations['page']['content']);

        View::setTitle($translations['page']['general']['title']);

        return View::make('login');

    }

    /**
     * Oturum açma sayfası aksiyonu.
     *
     * @return mixed
     */
    public function attempt()
    {

        $parameters = array(
            'username' => Input::get('username'),
            'password' => Input::get('password'),
        );

        $rules = array(
            'username' => 'required|min:5',
            'password' => 'required|min:5',
        );

        $translations = $this->translations('login');

        $validation = Validator::make($parameters, $rules, $translations['validation']);

        if ( $validation->fails() )
        {
            return Redirect::route('administrator.login')->withInput()->withErrors($validation->messages());
        }

        if ( Auth::authenticate($parameters) )
        {
            return Redirect::route('administrator.index');
        }
        else
        {
            return Redirect::route('administrator.login')->withInput();
        }

    }

    /**
     * Oturumu kapatma aksiyonu.
     *
     * @return mixed
     */
    public function logout()
    {

        Auth::logout();

        return Redirect::route('administrator.index');

    }

    /**
     * Dil değiştirme aksiyonu.
     *
     * @param $alias
     * @return mixed
     */
    public function language($alias)
    {

        $language = Languages::getByAlias($alias);

        if ( $language )
        {
            Session::setActiveLanguage($language->lang_alias);
        }

        return Redirect::refererOrRoute('administrator.index');

    }

}