<?php

return [

    'index'  => [
        'headtitle' => 'Index'
    ],

    'header' => [
        //dropdown logged
            'settings' => 'Ajustes',
            'profile' => 'Perfil',
            'notifications' => 'Notificaciones',
            'logout' => 'Cerrar sesion',
        //dropdown not logged
            'login' => 'Log in',
            'register' => 'Registrarse'
    ],
    'register' => [
        //form
        'title' => 'Registro de usuario',
        'name' => [
            'label' => 'Nombre',
            'placeholder' => 'Nombre',
            'title' => 'Ingrese su nombre',
        ],
        'last_name' => [
            'label' => 'Apellido',
            'placeholder' => 'Apellido',
            'title' => 'Ingrese su apellido',
        ],

        'email' =>[
            'label' => 'Email',
            'placeholder' => 'example@example.com',
            'title' => 'Ingrese su email',
        ],
        'phone'=>[
            'label' => 'Teléfono (opcional)',
            'title' => 'Ingrese su telefono',
            'placeholder' => '4555-7788',
        ],
        'password' =>[
            'label' => 'Contraseña',
            'placeholder' => 'Contraseña',
            'title' => 'Ingrese una contraseña de entre 8 y 22 caracteres de largo',
        ],
        'form' => [
            'term-condition' => [
                'label' => 'Acepto',
                'href'  => 'Terminos y condiciones',
            ],
            'login' => [
                'label' => '¿ya tienes cuenta?',
                'href'  => 'Logeate'
            ],
            'submit' => 'Register'
        ]

    ],
    'login' => [
        'login' => 'Inicio de sesion',
        'email'=> [
            'label' => 'Email',
            'placeholder' => 'example@example.com',
        ],
        'password'=> [
            'placeholder'=> 'Contraseña',
            'label' => 'contraseña',
            'show' => 'Mostrar contraseña',
        ],
        'submit' => 'Ingresar',
        'firsttime' =>[
            'label' => '¿primera vez en la pagina?',
            'href' => 'Registrarse',
        ],
        'forgot' =>[
            'label' => '¿has olvidado tu conraseña?',
            'href' => 'Recuperar',
        ],

    ],
    'forgot' => [
        //form
        'title' => 'Ingrese un email para recuperar su contraseña',
        'email' =>[
            'label' => 'Email',
            'placeholder' => 'example@example.com',
            'title' => 'Ingrese un email para recuperar su contraseña',
        ],
        'submitvalue' => 'Enviar email'
    ],
    'recovery' => [
        //message
        'passwordmismatch' => 'Las contraseñas no coinciden!',
        //form
        'title' => 'Cambiar contraseña',
        'password' =>[
            'label' => 'Contraseña',
            'placeholder' => 'Contraseña',
            'title' => 'EIngrese una contraseña de entre 8 y 22 caracteres de largo'
        ],
        'repeatpassword'=>[
            'label' => 'Repita la contraseña',
            'placeholder' => 'Contraseña',
            'title' => 'Ingrese una contraseña de entre 8 y 22 caracteres de largo',
        ],
        'submitvalue' => 'Cambiar contraseña'
    ],

    'contact' => [
        'title' =>'Contacto',

        'email'=> [
            'label' => 'Email',
            'placeholder' => 'example@example.com',
        ],
        'name' => [
            'label' => 'Nombre',
            'placeholder' => 'Nombre',
            'title' => 'Ingrese su nombre',
        ],
        'last_name' => [
            'label' => 'Apellido',
            'placeholder' => 'Apellido',
            'title' => 'Ingrese su apellido',
        ],
        'message' => [
            'label' => 'Mensaje',
            'placeholder' =>'Ingrese el mensaje'
        ],
        'submit' => 'Enviar'    ],

    [
        'swaptodata' => 'Cambiar información',
        'swaptopass' => 'Cambiar contraseña',],

];
