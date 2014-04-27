<?php

namespace Application\Library\External;

class Authorization {

    /**
     * Yetkileri tutar.
     *
     * @var array
     */
    private $permissions = array();

    /**
     * Başlangıç işlemleri.
     *
     * @return mixed
     */
    public function __construct()
    {

    }

    /**
     * Yeni bir yetki ekler.
     *
     * @param array $attributes
     */
    public function create(array $attributes)
    {

        $alias = $attributes['permission'];

        if ( $attributes['parent'] !== null )
        {
            $combine = $attributes['parent'].'.'.$attributes['permission'];

            $alias = implode('.children.', explode('.', $combine));;
        }

        if ( array_get($this->permissions, $alias) === null )
        {
            array_set($this->permissions, $alias, array(
                'title' => $attributes['title'],
                'description' => $attributes['description'],
                'extension' => $attributes['extension'],
            ));
        }

    }

    public function permissions()
    {

        return $this->permissions;

    }

    public function getCustomFormat(array $accessList = array(), array $options = array(), $children = null)
    {

        $options = $options ?: $this->permissions;

        $result = array();

        foreach($options as $permission=>$values)
        {

            $return = array(
                'id' => $children ? $children.'.'.$permission : $permission,
                'label' => $values['title'],
                'inode' => false,
                'checkbox' => true,
                'radio' => false,
            );

            if ( isset($values['description']) && $values['description'] )
            {
                $return['label'] = $return['label'].' ('.$values['description'].')';
            }

            $return['label'] = $return['label'].' ['.$values['extension'].']';

            if ( array_get($accessList, $return['id']) )
            {
                $return['checked'] = true;
            }

            if (isset($values['children']) && $values['children'])
            {
                $return['inode'] = true;
                $return['branch'] = $this->getCustomFormat($accessList, $values['children'], $permission);
            }
            else
            {
                $return['checkbox'] = true;
            }

            $result[] = $return;

        }

        return $result;

    }

}