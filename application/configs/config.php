<?php
/**
 * ${NAME} file
 *
 * @author Oleksandr Muzychenko <avionwd@gmail.com>
 */

$default = [
    'application' => [
        'baseDir'        => dirname(__DIR__),
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir'      => __DIR__ . '/../models/',
        'formsDir'       => __DIR__ . '/../models/',
        'viewsDir'       => __DIR__ . '/../views/',
        'pluginsDir'     => __DIR__ . '/../plugins/',
        'cacheDir'       => __DIR__ . '/../cache/',
        'docRoot'        => __DIR__ . '/../public/',
        'baseUri'        => '/',
    ],
    'database' => [
        "adapter"  => 'postgresql',
        "host"     => 'db',
        "port"     => '5432',
        "username" => getenv('POSTGRES_USER'),
        "password" => getenv('POSTGRES_PASSWORD'),
        "dbname"   => getenv('POSTGRES_USER'),
        "schema"   => 'public'
    ],
    // TODO Change config with real SMTP settings
//    'mailer' => [
//        'driver' => 'smtp',
//        'host' => 'smtp.host.com',
//        'port' => 587,
//        'from'   => [
//            'email' => 'test@example.com',
//            'name'    => 'User Name',
//        ],
//        'encryption' => 'tls',
//        'username' => 'username@example.com',
//        'password' => 'secret',
//        'viewsDir' => __DIR__ . '/../views/email/',
//    ],
    'adminEmail' => 'admin@example.com'
];


return new Phalcon\Config($default);