<?php

return array(

    'page' => array(
        'general' => array(
            'title' => 'User Login'
        ),
        'content' => array(
            'title' => 'Sing in',
            'username' => 'Username',
            'password' => 'Password',
            'submit' => 'Sing in',
        )
    ),

    'validation' => array(
        'username.required' => 'Username is required.',
        'username.min' => 'Kullanıcı adı en az %s karakterden oluşmalıdır.',
        'password.required' => 'Password is required.',
        'password.min' => 'Şifreniz en az %s karakterden oluşmalıdır.',
    ),

);