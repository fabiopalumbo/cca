<?php
return [
    'usercontroller' => [
        'register' => [
            'userpreviouslydeleted' => 'User previously deleted',
            'emailnotavailable' => 'Email already taken',
            'success' => 'Successful register!',
        ],
        'update' =>[
            'userupdated' => 'User updated!',
            'passwordupdate' => 'Password updated!',
            'passwordnotupdated' => 'There was an error. Try again.'
        ],
        'delete' => [
            'userdeleted' => 'User successfully deleted',
            'usernotdeleted' => 'There was an error deleting the user. Try again.'
        ],
        'login' => [
            'error' => 'Error while logging. Try again',
            'success' => 'Success!',
            'wrong' => 'Wrong email or password'
        ],
        'logout' => [
            'bye' => 'We hope to see you again soon!'
        ],
        'active' => [
            'success' => 'User activated!',
            'error' => 'There was an error activating the user. Try again.'
        ],
        'recovery' => [
            'emailsubject' => 'Password recovery',
            'emailsent' => 'We have sent you an email with instructions to recover your account.',
            'emailnotsent' => 'There was an error sending the email. Try again in a moment.',
            'success' => 'Password changed!',
            'error' => 'There was an error processing your request. Try again in a moment.'
        ],
        'image' => [
            'success' => 'Image uploaded!',
            'error' => 'There was an error uploading the image. Try again in a moment.'
        ],
        'contact' => [
            'success' => 'Your message has been sent',
            'error' => 'messages must contain more than 5 characters'

        ],

    ]
];