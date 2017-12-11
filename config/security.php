<?php

return [
    'driver' => 'database',
    'lifetime' => 30,
    'remembertime' => 60*24*14,
    'expire_on_close' => false,
    'encrypt' => false,
    'connection' => null,
    'table' => 'user_sessions',
    'lottery' => [10, 100],
    'cookie' => 'user_session',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'http_only' => true,
];
