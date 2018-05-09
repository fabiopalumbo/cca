<?php

return [
    'credentials' => [
        'key'    => 'AKIAISV2KGR6JFBZMWSQ',
        'secret' => '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',
    ],
    'region' => 'us-east-1',
    'version' => 'latest',

    'cdn_url'       => 'https://s3.amazonaws.com/nuestrosautos/',
    'cdn_bucket'    => 'nuestrosautos',

  // You can override settings for specific services
    'Ses' => [
        'region' => 'us-east-1',
    ],
];