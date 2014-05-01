<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Thoriap\Core\Factory\Administrator;
use Thoriap\Alias\Model\Languages;
use Thoriap\Alias\Model\Plugins;
use Thoriap\Alias\Model\Groups;

class KernelAdministrator extends Administrator {

    /**
     * Olmasa da olur.
     *
     * @return mixed|void
     */
    public function init()
    {

    }

    /**
     * Grupları listeleme sayfası.
     *
     * @return mixed
     */
    public function groups()
    {

        $groups = Groups::getAll();

        View::setTitle('Gruplar');

        return View::make('groups/index', compact('groups'));

    }

    /**
     * Grup oluşturma sayfası.
     *
     * @return mixed
     */
    public function createGroup()
    {

        View::setTitle('Grup Oluştur');

        return View::make('groups/create');

    }

    /**
     * Grup düzenleme sayfası.
     *
     * @param integer $groupId
     * @return mixed
     */
    public function editGroup($groupId)
    {

        $group = Groups::find($groupId);

        View::setTitle('Grup Düzenle');

        return View::make('groups/edit', compact('group'));

    }

    /**
     * Yetkilendirme sayfası.
     *
     * @param integer $groupId
     * @return mixed
     */
    public function editPermissions($groupId)
    {

        $group = Groups::find($groupId);

        $group_access = json_encode(PermissionManager::getCustomFormat(unserialize($group->group_access)));

        View::setTitle('Grup Yetkilendirme');

        return View::make('groups/permissions', compact('group', 'group_access'));

    }

    /**
     * Yetkilendirme sayfası aksiyonu.
     *
     * @param integer $groupId
     * @return mixed
     */
    public function editPermissionsAction($groupId)
    {

        $permissions = json_decode(Input::get('group_access'));

        $accessList = array();

        foreach($permissions as $permission)
        {
            array_set($accessList, $permission, true);
        }

        Groups::update($groupId, array('group_access' => serialize($accessList)));

        return Redirect::refresh();

    }

    /**
     * Grup oluşturma sayfası aksiyonu.
     *
     * @return mixed
     */
    public function createGroupAction()
    {

        $parameters = array(
            'group_name' => Input::get('group_name'),
            'group_description' => Input::get('group_description'),
            'group_access' => serialize(array()),
        );

        $rules = array(
            'group_name' => 'required|unique:groups,group_name',
        );

        $messages = array(
            'group_name.required' => 'Grup adı zorunludur.',
            'group_name.unique' => 'Bu isime sahip bir grup zaten var.',
        );

        $validation = Validator::make($parameters, $rules, $messages);

        if ( $validation->fails() )
        {
            return Redirect::route('groups.create')->withInput()->withErrors($validation->messages());
        }

        Groups::insert($parameters);

        return Redirect::route('groups.index');

    }

    /**
     * Grup düzenleme sayfası aksiyonu.
     *
     * @param $groupId
     * @return mixed
     */
    public function editGroupAction($groupId)
    {

        $parameters = array(
            'group_name' => Input::get('group_name'),
            'group_description' => Input::get('group_description'),
        );

        $rules = array(
            'group_name' => 'required|unique:groups,group_name,'.$groupId.',group_id',
        );

        $messages = array(
            'group_name.required' => 'Grup adı zorunludur.',
            'group_name.unique' => 'Bu isime sahip bir grup zaten var.',
        );

        $validation = Validator::make($parameters, $rules, $messages);

        if ( $validation->fails() )
        {
            return Redirect::route('groups.edit', $groupId)->withInput()->withErrors($validation->messages());
        }

        Groups::update($groupId, $parameters);

        return Redirect::route('groups.index');

    }

    /**
     * Eklentileri listeleme sayfası.
     *
     * @return mixed
     */
    public function extensions()
    {

        $extensions = Plugins::getAll();

        foreach($extensions as &$extension)
        {
            $extension->get = Registry::getStatement($extension->plugin_name);
        }

        $translations = $this->translations('plugins/index');

        View::setTitle($translations['general']['title']);

        View::setTranslations($translations['content']);

        return View::make('extensions/index', compact('extensions'));

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

}