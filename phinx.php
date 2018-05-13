<?php
return [
    'paths'        => [
        'migrations' => './migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database'        => getenv('HAPPY_ENV'),
        getenv('HAPPY_ENV')     => [
            'adapter' => 'mysql',
            'host'    => getenv('HAPPY_MYSQL_DATABASE_HOST'),
            'name'    => getenv('HAPPY_MYSQL_DATABASE'),
            'user'    => getenv('HAPPY_MYSQL_USER'),
            'pass'    => getenv('HAPPY_MYSQL_PASSWORD'),
            'port'    => 3306,
        ],
    ],
];
