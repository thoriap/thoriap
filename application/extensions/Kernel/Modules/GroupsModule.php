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

use View, Redirect, Input, Validator, PermissionManager;
use Thoriap\Alias\Models\Groups;
use Thoriap\Core\Factory\ExtensionsBase;

class GroupsModule extends ExtensionsBase {

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

}