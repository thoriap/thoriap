<?php

/*
 * This file is part of the Thoriap package.
 *
 * (c) Yalçın Ceylan <creator@thoriap.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thoriap\View\Engine;

class Thor {

    /**
     * Şablon yolunu tutar.
     *
     * @var string
     */
    protected $template;

    /**
     * Şablonun kaynağını tutar.
     *
     * @var string
     */
    protected $resource;

    /**
     * Şablon kaynağını okur ve tanımlar.
     *
     * @return void
     */
    protected function setResource()
    {

        $this->resource = file_get_contents($this->template);

    }

    /**
     * Döngü ve benzeri blokları yorumlar.
     *
     * @return void
     */
    protected function interpretBlocksOfLoop()
    {

        $parameters = array('if', 'while', 'for', 'foreach');

        $this->resource = preg_replace('/@\s?('.implode('|', $parameters).')\s?\(\s?(.*?)\s?\);?$/m', '<?php ${1} (${2}) { ?>', $this->resource);

        $this->resource = preg_replace('/@\s?(else\s?if)\s?\(\s?(.*?)\s?\);?$/m', '<?php } ${1} (${2}) { ?> ', $this->resource);

        $this->resource = preg_replace('/@\s?(else);?$/m', '<?php } ${1} { ?>', $this->resource);

    }

    /**
     * Bitiş bloklarını yorumlar.
     *
     * @return void
     */
    protected function interpretBlocksOfEnd()
    {

        $parameters = array('endif', 'endwhile', 'endfor', 'endforeach');

        $this->resource = preg_replace('/@\s?('.implode('|', $parameters).');?$/m', '<?php } ?>', $this->resource);

    }

    /**
     * Diğer blokları yorumlar.
     *
     * @return void
     */
    protected function interpretOtherBlocks()
    {

        $parameters = array('extends', 'section');

        $this->resource = preg_replace('/@\s?('.implode('|', $parameters).')\s?\((\'(.*?)\'|\"(.*?)\")\);?$/m', '<?php View::section(${2}) ?>', $this->resource);

        $this->resource = preg_replace('/{{\s?(.*?)\s?}}/', '<?php echo ${1} ?>', $this->resource);

    }

    /**
     * Başlangıç işlemleri.
     *
     * @param string $template
     * @return mixed
     */
    public function __construct($template)
    {

        $this->template = $template;

    }

    /**
     * Şablonu yorumlama sürecini başlatır.
     *
     * @return void
     */
    public function startProcess()
    {

        $this->setResource();

        $this->interpretBlocksOfLoop();

        $this->interpretBlocksOfEnd();

        $this->interpretOtherBlocks();

    }

    /**
     * Yorumlanmış kaynağın çıktısını verir.
     *
     * @return string
     */
    public function getOutput()
    {

        return $this->resource;

    }

}