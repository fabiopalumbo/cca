<?php

return [

    'ml' => [
        'app_ID'        => env('PORTAL_ML_APPID',6653552701183140),
        'secret_key'    => env('PORTAL_ML_SECRETKEY','QsP2RaZUS33gaPnsfHjdRhiYovB4IIhA'),
        'url_redirect'  => env('PORTAL_ML_URLREDIRECT',"https://staging.nuestrosautos.cca.org.ar/api/mercadolibre/login"),

    ],

    'fb' => [
        'app_id'        => '1131927070172203',
        'app_secret'    => '937860d31cfd6d5349a90637f8f3a63b',
        'default_graph_version' => 'v2.2',

    ],

    'ac' => [
        'app_key' => "dd15deb217e14c07816e2878e19aa626",
        'app_secret' => "b13b82565abd4015af0aeab3ea418a03",
	    'app_domain' => "http://www.autocosmos.com.ar",
        'user_signature' => "MwqSNTWSi+/n8PIMR5zNEg=="
    ],

    'dm' => [
        'provider' => 'GRUPO_CCA',
        'key' => '3453ceda832a83687d0905c2fcdfbe9c',
    ]
];
