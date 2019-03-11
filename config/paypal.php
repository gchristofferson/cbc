<?php
return [
    'client_id' => env('PAYPAL_CLIENT', ''),
    'secret' => env('PAYPAL_SECRET', ''),
    'settings' => array(
        'mode' => env('PAYPAL_MODE,', 'sandbox'),
        'http.ConnectionTimeOut' => 90,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR',
    )
];
