<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Thoriap\Core\Factory\Permissions;

class KernelPermissions extends Permissions {

    public function init()
    {

        $this->create(array('title' => 'Üst Düzey Yönetici', 'description' => '', 'permission' => 'administrator'));

        $this->create(array('title' => 'Eklenti Yönetimi', 'description' => '', 'permission' => 'extensions'), function($sub){

            $sub->create(array('title' => 'Görüntüleme', 'description' => '', 'permission' => 'show'))
                ->create(array('title' => 'Yükleme', 'description' => '', 'permission' => 'install'))
                ->create(array('title' => 'Kaldırma', 'description' => '', 'permission' => 'uninstall'))
                ->create(array('title' => 'Başlatma', 'description' => '', 'permission' => 'start'))
                ->create(array('title' => 'Durdurma', 'description' => '', 'permission' => 'stop'));

        });

        $this->create(array('title' => 'Grup Yönetimi', 'description' => '', 'permission' => 'groups'), function($sub){

            $sub->create(array('title' => 'Görüntüleme', 'description' => '', 'permission' => 'show'))
                ->create(array('title' => 'Oluşturma', 'description' => '', 'permission' => 'create'))
                ->create(array('title' => 'Düzenleme', 'description' => '', 'permission' => 'edit'))
                ->create(array('title' => 'Yetkilendirme', 'description' => '', 'permission' => 'access'))
                ->create(array('title' => 'Silme', 'description' => '', 'permission' => 'delete'));

        });

        $this->create(array('title' => 'Kullanıcı Yönetimi', 'description' => '', 'permission' => 'users'), function($sub){

            $sub->create(array('title' => 'Görüntüleme', 'description' => '', 'permission' => 'show'))
                ->create(array('title' => 'Oluşturma', 'description' => '', 'permission' => 'create'))
                ->create(array('title' => 'Düzenleme', 'description' => '', 'permission' => 'edit'))
                ->create(array('title' => 'Yetkilendirme', 'description' => '', 'permission' => 'access'))
                ->create(array('title' => 'Silme', 'description' => '', 'permission' => 'delete'));

        });

    }

}