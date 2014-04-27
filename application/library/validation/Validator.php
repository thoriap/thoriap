<?php

namespace Application\Library\Validation;

use Application\Library\Adapter\Adapter;

class Validator {

    /**
     * Adaptörü tutar.
     *
     * @var Adapter
     */
    private $adapter;

    /**
     * Verileri tutar.
     *
     * @var array
     */
    private $fields;

    /**
     * Kuralları tutar.
     *
     * @var array
     */
    private $rules;

    /**
     * Bildirimleri tutar.
     *
     * @var array
     */
    private $messages;

    /**
     * Hataları tutar.
     *
     * @var array
     */
    private $errors;

    /**
     * Argümanları tutar.
     *
     * @var array
     */
    private $arguments;

    /**
     * Hata var mı yok mu?
     *
     * @var bool
     */
    private $fails = false;

    /**
     * Başlangıç işlemleri.
     *
     * @param $fields
     * @param $rules
     * @param $messages
     * @param Adapter $adapter
     * @return mixed
     */
    public function __construct($fields, $rules, $messages, Adapter $adapter)
    {

        $this->fields = $fields;

        $this->rules = $rules;

        $this->messages = $messages;

        $this->adapter = $adapter;

        $this->startValidate();

    }

    /**
     * Hata var mı yok mu?
     *
     * @return bool
     */
    public function fails()
    {
        return $this->fails;
    }

    /**
     * Hataları getir.
     *
     * @return array
     */
    public function messages()
    {
        return $this->errors;
    }

    /**
     * Kuralları böler.
     *
     * @param $rules
     * @return array
     */
    private function parse($rules)
    {

        $result = array();

        $parse = explode('|', $rules);

        foreach($parse as $item)
        {

            $arguments = explode(':', $item, 2);

            if ( count($arguments) == 2 )
            {
                list($rule, $parameters) = $arguments;

                $this->arguments[$rule] = explode(',', $parameters);

                $result[] = $rule;

            }
            else
            {
                $rule = $arguments[0];

                $this->arguments[$rule] = array();

                $result[] = $rule;
            }

        }

        return $result;

    }

    /**
     * Doğrulamayı başlatır.
     *
     * @return void
     */
    private function startValidate()
    {

        foreach($this->rules as $name=>$rule)
        {

            $value = $this->fields[$name];

            $rules = $this->parse($rule);

            foreach($rules as $method)
            {

                $arguments = $this->arguments[$method];

                $validate = 'validate'.ucfirst($method);

                $result = $this->{$validate}($value, $arguments);

                if ( $result == false )
                {

                    $this->fails = true;

                    /*

                    $message = $this->messages[$name.'.'.$method];

                    if ( $arguments )
                    {
                        array_unshift($arguments, $message);

                        $message = call_user_func_array('sprintf', $arguments);
                    }

                    $this->errors[$name][] = $message;

                    */

                    $this->errors[$name][] = $this->messages[$name.'.'.$method];

                }

            }

        }

    }

    /**
     * Zorunlu alan doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return bool
     */
    private function validateRequired($value, array $arguments)
    {

        if ( is_null($value) )
        {
            return false;
        }
        else if (is_string($value) && trim($value) === '')
        {
            return false;
        }

        return true;

    }

    /**
     * Benzersiz kayıt doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return bool
     */
    private function validateUnique($value, array $arguments)
    {

        if ( count($arguments) == 3 )
        {
            $arguments[] = 'id';
        }

        $count = count($arguments);

        if ( $count == 2 )
        {

            list($table, $column) = $arguments;

            $query = "SELECT COUNT(`{$column}`) as `result` FROM `{$table}` WHERE `{$column}` = ?";

            $prepare = $this->adapter->prepare($query);

            $prepare->execute(array($value));

        }

        else if ( $count == 4 )
        {

            list($table, $column, $except, $primary) = $arguments;

            $query = "SELECT COUNT(`{$column}`) as `result` FROM `{$table}` WHERE `{$column}` = ? AND `{$primary}` <> ?";

            $prepare = $this->adapter->prepare($query);

            $prepare->execute(array($value, $except));

        }

        if ( $prepare->fetchColumn() )
        {
            return false;
        }

        return true;

    }

    /**
     * Eposta adresi doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return bool
     */
    private function validateEmail($value, array $arguments)
    {

        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

    }

    /**
     * URL adresi doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return bool
     */
    private function validateUrl($value, array $arguments)
    {

        return filter_var($value, FILTER_VALIDATE_URL) !== false;

    }

    /**
     * Sadece alfabetik karakterler doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return int
     */
    private function validateAlpha($value, array $arguments)
    {

        return preg_match('/^([a-z])+$/i', $value);

    }

    /**
     * Sadece alfabetik ve numerik karakterler doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return int
     */
    private function validateAlphaNum($value, array $arguments)
    {

        return preg_match('/^([a-z0-9])+$/i', $value);

    }

    /**
     * Sadece alfabetik, numerik, alt ve normal tire doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return int
     */
    private function validateAlphaDash($value, array $arguments)
    {

        return preg_match('/^([a-z0-9_-])+$/i', $value);

    }

    /**
     * Sayı doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return bool
     */
    private function validateInteger($value, array $arguments)
    {

        return filter_var($value, FILTER_VALIDATE_INT) !== false;

    }

    /**
     * Numerik doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return bool
     */
    private function validateNumeric($value, array $arguments)
    {

        return is_numeric($value);

    }

    /**
     * Minimum uzunluk doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return bool
     */
    private function validateMin($value, array $arguments)
    {

        $length = $this->getStringSize($value);

        if ( $length < $arguments[0] )
        {
            return false;
        }

        return true;

    }

    /**
     * Maksimum uzunluk doğrulaması yapar.
     *
     * @param $value
     * @param array $arguments
     * @return bool
     */
    private function validateMax($value, array $arguments)
    {

        $length = $this->getStringSize($value);

        if ( $length > $arguments[0] )
        {
            return false;
        }

        return true;

    }

    /**
     * Belirtilen dizgenin uzunluğunu verir.
     *
     * @param $value
     * @return int
     */
    private function getStringSize($value)
    {

        return mb_strlen($value, 'UTF-8');

    }

}