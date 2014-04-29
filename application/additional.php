<?php

if ( !function_exists('object_get'))
{
    /**
     * Nokta ile belirtilen elemanı nesneden getirir.
     *
     * @param $object
     * @param $key
     * @param null $default
     * @return null
     */
    function object_get($object, $key, $default = null)
    {
        if (is_null($key) || trim($key) == '') return $object;

        foreach (explode('.', $key) as $segment)
        {
            if ( ! is_object($object) || ! isset($object->{$segment}))
            {
                return $default;
            }

            $object = $object->{$segment};
        }

        return $object;
    }
}

if ( !function_exists('array_get'))
{
    /**
     * Nokta ile belirtilen elemanı diziden getirir.
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
            if ( ! is_array($array) || ! array_key_exists($segment, $array))
            {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }
}

if ( !function_exists('array_set'))
{
    /**
     * Nokta ile belirtilen elemanı dizi de tanımlar.
     *
     * @param $array
     * @param $key
     * @param $value
     * @return mixed
     */
    function array_set(&$array, $key, $value)
    {
        if (is_null($key)) return $array = $value;

        $keys = explode('.', $key);

        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            if ( ! isset($array[$key]) || ! is_array($array[$key]))
            {
                $array[$key] = array();
            }

            $array =& $array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}

if ( !function_exists('array_forget'))
{
    /**
     * Nokta ile belirtilen elemanı diziden siler.
     *
     * @param $array
     * @param $key
     */
    function array_forget(&$array, $key)
    {
        $keys = explode('.', $key);

        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            if ( ! isset($array[$key]) || ! is_array($array[$key]))
            {
                return;
            }

            $array =& $array[$key];
        }

        unset($array[array_shift($keys)]);
    }
}

if ( !function_exists('array_pull'))
{
    /**
     * Belirtilen elemanı diziden getirir ve siler.
     *
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed
     */
    function array_pull(&$array, $key, $default = null)
    {
        $value = array_get($array, $key, $default);

        array_forget($array, $key);

        return $value;
    }
}