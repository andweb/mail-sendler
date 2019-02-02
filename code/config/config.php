<?php

return [
    // QUEUE SETTINGS
    'queue' => [
        'server'    => 'gearman',
        'port'      => '4730',
    ],
    
    // MAIL SETTINGS
    'mail' => [
        'driver'    => 'smtp',
        'host'      => 'smtp.mailtrap.io',
        'port'      => '465',
        'username'  => '3e3131adfd4175',
        'password'  => '4021cff72c2556',
        'encryption'=> 'tls',
        'from'      => '66316d3ea6-c2bc83@inbox.mailtrap.io',
        'from_name' => 'Mail Sendler',
    ]
];