

<?php

return [

    'index'  => [

        'headtitle' => 'Index'

    ],

    'header' => [

        //dropdown logged

        'settings' => 'Settings',

        'profile' => 'Profile',

        'notifications' => 'Notifications',

        'logout' => 'Log out',

        //dropdown not logged

        'login' => 'Log in',

        'register' => 'Register'

    ],

    'register' => [

        'headtitle' => 'Register',

        //form

        'title' => 'User register',

        'name' => [

            'label' => 'Name',

            'placeholder' => 'Name',

            'title' => 'Enter your first name',

        ],

        'last_name' => [

            'label' => 'Last name',

            'placeholder' => 'Last name',

            'title' => 'Enter your last name',

        ],

        'email' =>[

            'label' => 'Email',

            'placeholder' => 'example@example.com',

            'title' => 'Enter your email',

        ],

        'phone'=>[

            'label' => 'Phone (optional)',

            'title' => 'Enter your phone',

            'placeholder' => '4555-7788',

        ],

        'password' =>[

            'label' => 'Password',

            'placeholder' => 'Password',

            'title' => 'Enter a password between 8 and 22 characters long',

        ],

        'form' => [

            'term-condition' => [

                'label' => 'I accept the ',

                'href'  => 'terms and agreements',

            ],

            'login' => [

                'label' => 'Already you have an account?',

                'href'  => 'Login'

            ],

            'submit' => 'Register'

        ]

    ],

    'login' => [

        'headtitle' => 'Login',

        'login' => 'Login',

        'email'=> [

            'label' => 'Email',

            'placeholder' => 'example@example.com',

        ],

        'password'=> [

            'placeholder'=> 'Password',

            'label' => 'Password',

            'show' => 'Show password',

        ],

        'submit' => 'submit',

        'firsttime' =>[

            'label' => 'First time in the page?',

            'href' => 'Register',

        ],

        'forgot' =>[

            'label' => 'forgot password?',

            'href' => 'Recover',

        ],

    ],

    'forgot' => [

        'headtitle' => 'Forgot your password?',

        //form

        'title' => 'Enter an email to recover your password',

        'email' =>[

            'label' => 'Email',

            'placeholder' => 'example@example.com',

            'title' => 'Enter an email to recover your password',

        ],

        'submitvalue' => 'Send email'

    ],

    'recovery' => [

        'headtitle' => 'Password recovery',

        //message

        'passwordmismatch' => 'Passwords do not match!',

        //form

        'title' => 'Change your password',

        'password' =>[

            'label' => 'Password',

            'placeholder' => 'Password',

            'title' => 'Enter a password between 8 and 22 characters long',

        ],

        'repeatpassword'=>[

            'label' => 'Repeat the password',

            'placeholder' => 'Password',

            'title' => 'Enter a password between 8 and 22 characters long',

        ],

        'submitvalue' => 'Change password'

    ],

    'modify' => [

        'headtitle' => 'Modify your information',

        //form

        'title' => 'Modify your information',

        'name' => [

            'label' => 'Name',

            'placeholder' => 'Name',

            'title' => 'Enter your first name',

        ],

        'last_name' => [

            'label' => 'Last name',

            'placeholder' => 'Last name',

            'title' => 'Enter your last name',

        ],

        'phone'=>[

            'label' => 'Phone (optional)',

            'title' => 'Enter your phone',

            'placeholder' => '4555-7788',

        ],

        'oldpassword' =>[

            'label' => 'Current password',

            'placeholder' => 'Current password',

            'title' => 'Enter your current password',

        ],

        'newpassword' =>[

            'label' => 'New password',

            'placeholder' => 'New password',

            'title' => 'Enter a password between 8 and 22 characters long',

        ],

        'repeatpassword' => [

            'label' => 'Repeat password',

            'placeholder' => 'Repeat password',

            'title' => 'Enter a password between 8 and 22 characters long',

        ],

        'form' => [

            'submit' => 'Modify'

        ],

        'image' => [

            'label' => 'Upload a profile photo',

            'title' => 'Upload a profile photo'

        ],

        'swaptodata' => 'Change information',

        'swaptopass' => 'Change password',

    ],

    'contact' => [

'title' =>'Contact',



      'email'=> [

               'label' => 'Email',

               'placeholder' => 'example@example.com',

           ],

       'name' => [

               'label' => 'Name',

                'placeholder' => 'Name',

                'title' => 'Enter your first name',

           ],

        'last_name' => [

               'label' => 'Last name',

               'placeholder' => 'Last name',

               'title' => 'Enter your last name',

            ],

        'message' => [

              'label' => 'message',

              'placeholder' =>'Enter your message'

              ],

       'submit' => 'Send'
        ]

];

