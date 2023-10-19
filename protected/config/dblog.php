<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host='.PG_HOST.';dbname='.DBSMKKLOG,
    'username' => PG_USER,
    'password' => PG_PASS,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
