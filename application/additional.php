<?php

if ( !function_exists('array_get') )
{
    /**
     * Belirtilen diziden eleman getirir.
     *
     * @param $array
     * @param $key
     * @param null $default
     * @return null
     */
    function array_get($array, $key, $default = null)
    {

        if (is_null($key)) return $array;

        if (isset($array[$key])) return $array[$key];

        foreach (explode('.', $key) as $segment)
        {
            if (!is_array($array) or !array_key_exists($segment, $array))
            {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }
}

if ( !function_exists('array_set') )
{
    /**
     * Belirtilen diziye eleman tanımlar.
     *
     * @param $array
     * @param $key
     * @param $value
     * @return mixed
     */
    function array_set(&$array, $key, $value) {

        if (is_null($key)) return $array = $value;

        $keys = explode('.', $key);

        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            if (!isset($array[$key]) or !is_array($array[$key]))
            {
                $array[$key] = array();
            }
            $array =& $array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;

    }
}