<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/*
|--------------------------------------------------------------------------
| Hazırlanıyor.
|--------------------------------------------------------------------------
|
| Sistemin çalışması için gerekli olan dizinler tanımlanır ve
| ilgili dosyalar dahil edilir. Daha sonra ise sistemin bir kısmı
| çalıştırılıp kütüphaneler çağrılmaya hazır hale getirilir..
|
*/
$application = require __DIR__.'/bootstrap/application.php';

/*
|--------------------------------------------------------------------------
| Çalıştırılıyor.
|--------------------------------------------------------------------------
|
| Gerekli olan dizin ayarları, dosyalar ve kütüphaneler hazırlandığına
| göre uygulamanın çalışmaya başlaması için hiçbir engel bulunmamaktadır.
| Uygulama başlatılıyor.
|
*/
$application->run();