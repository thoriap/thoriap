<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kernel;

use Thoriap\Core\Factory\PermissionsBase;

class Permissions extends PermissionsBase {

    public function init()
    {

        $this->create(array('title' => 'Üst Düzey Yönetici', 'permission' => 'administrator'));

        $this->create(array('title' => 'Eklenti Yönetimi', 'permission' => 'extensions'), function($sub){

            $sub->create(array('title' => 'Görüntüleme', 'permission' => 'show'))
                ->create(array('title' => 'Yükleme', 'permission' => 'install'))
                ->create(array('title' => 'Kaldırma', 'permission' => 'uninstall'))
                ->create(array('title' => 'Başlatma', 'permission' => 'start'))
                ->create(array('title' => 'Durdurma', 'permission' => 'stop'));

        });

        $this->create(array('title' => 'Grup Yönetimi', 'permission' => 'groups'), function($sub){

            $sub->create(array('title' => 'Görüntüleme', 'permission' => 'show'))
                ->create(array('title' => 'Oluşturma', 'permission' => 'create'))
                ->create(array('title' => 'Düzenleme', 'permission' => 'edit'))
                ->create(array('title' => 'Yetkilendirme', 'permission' => 'access'))
                ->create(array('title' => 'Silme', 'permission' => 'delete'));

        });

        $this->create(array('title' => 'Kullanıcı Yönetimi', 'permission' => 'users'), function($sub){

            $sub->create(array('title' => 'Görüntüleme', 'permission' => 'show'))
                ->create(array('title' => 'Oluşturma', 'permission' => 'create'))
                ->create(array('title' => 'Düzenleme', 'permission' => 'edit'))
                ->create(array('title' => 'Yetkilendirme', 'permission' => 'access'))
                ->create(array('title' => 'Silme', 'permission' => 'delete'));

        });

    }

}