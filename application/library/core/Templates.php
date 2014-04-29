<?php

namespace Application\Library\Core;

use Config;
use Application\Library\Alias\Model\Themes;

class Templates {

    /**
     * Başlangıç işlemleri.
     *
     * @return mixed
     */
    public function __construct()
    {

        // Temaları getir.
        $allThemes = $this->refreshThemes();

        // Bulunan temaları döndürelim.
        foreach($allThemes as $name=>$files)
        {

            // Veritabanından bilgilerini getir.
            $theme = Themes::getOneByName($name);

            // Bu aradığımız ve aktif olan sanırım.
            if (isset($theme->theme_active) && $theme->theme_active == 1)
            {
                // Evet bunu tanımlıyoruz.
                Config::set("application.template", $name);
            }

        }

        Config::set("application.templates", $allThemes);

    }

    /**
     * Temaları yeniler ve çıktı verir.
     *
     * @return array
     */
    private function refreshThemes()
    {

        // Dizindeki temaları alalım.
        $themes = $this->getThemes();

        // Bakalım temalarımız güncel mi?
        foreach ($themes as $name=>$value)
        {
            // Bu temadan uygulamanın haberi yok galiba?
            if ( !Themes::getOneByName($name) )
            {
                // Al bunu da ekle, haberin olsun.
                Themes::insert(array('theme_name' => $name));
            }
        }

        return $themes;

    }

    /**
     * Dizindeki kullanılabilir temaları verir.
     *
     * @return array
     */
    private function getThemes()
    {

        $themes = array();

        // Dizine bakalım kimler varmış?
        foreach ( glob(theme_path().'/interface/*', GLOB_ONLYDIR) as $template )
        {
            // Temanın adını tanımlıyoruz.
            $templateName = basename($template);

            // Standartlarımıza uyuyor mu?
            if (ctype_lower($templateName))
            {
                // Şunun ismindeki harfleri de küçültelim.
                $name = strtolower(basename($template));

                // Gerekli olan dosyaları belirtelim.
                $files = (object) array (
                    'directory' => $template,
                    'config' => $template.'/configuration.ini',
                    'index' => $template.'/index.thor',
                    'header' => $template.'/header.thor',
                    'footer' => $template.'/footer.thor',
                );

                // Dosyaların varlığını sınayalım.
                if ( is_readable($files->config) && is_readable($files->index) )
                {
                    // Evet bu template kullanılabilir...
                    $themes[$templateName] = $files;
                }
            }
        }

        return $themes;

    }

}