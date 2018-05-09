<?php
return [
    'usercontroller' => [
        'register' => [
            'userpreviouslydeleted' => 'Usuario previamente eliminado',
            'emailnotavailable' => 'Email en uso',
            'success' => '¡Registro exitoso!',
        ],
        'update' =>[
            'userupdated' => '¡Usuario actualizado!',
            'passwordupdate' => '¡Contraseña actualizada!',
            'passwordnotupdated' => 'Hubo un error. Intente nuevamente.'
        ],
        'delete' => [
            'userdeleted' => 'Usuario eliminado',
            'usernotdeleted' => 'Hubo un error eliminando el usuario. Intente nuevamente'
        ],
        'login' => [
            'error' => 'Hubo un error al intentar loguear. Intene nuevamente',
            'success' => '¡Login exitoso!',
            'wrong' => 'Email o contraseña incorrectos'
        ],
        'logout' => [
            'bye' => '¡Esperamos verte de vuelta pronto!'
        ],
        'active' => [
            'success' => '¡Usuario activado!',
            'error' => 'Hubo un error activando el usuario. Intente nuevamente'
        ],
        'recovery' => [
            'emailsubject' => 'Recupero de contraseña',
            'emailsent' => 'Te enviamos un email con instrucciones para recuperar la contraseña.',
            'emailnotsent' => 'Hubo un error al enviar el email. Intente nuevamente en unos momentos',
            'success' => '¡Contraseña actualizada!',
            'error' => 'Hubo un error procesando tu pedido. Intente nuevamente en unos momentos'
        ],
        'image' => [
            'success' => '¡Imagen subida!',
            'error' => 'Hubo un error al subir la imagen. Intente nuevamente en unos momentos'
        ],

    ]
];