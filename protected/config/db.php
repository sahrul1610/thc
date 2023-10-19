<?php

$PG_HOST = getenv('PG_HOST') ? getenv('PG_HOST') : PG_HOST;
$DBSMKK = getenv('DBSMKK') ? getenv('DBSMKK') : DBSMKK;
$DBSMKKPORT = getenv('DBSMKKPORT') ? getenv('DBSMKKPORT') : DBSMKKPORT;
$PG_USER = getenv('PG_USER') ? getenv('PG_USER') : PG_USER;
$PG_PASS = getenv('PG_PASS') ? getenv('PG_PASS') : PG_PASS;

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=host.docker.internal;dbname=hcc;port=5439',
    'username' => 'postgres',
    'password' => 'test123',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
