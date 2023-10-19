<?php
$YII_ENV = getenv('YII_ENV') ? getenv('YII_ENV') : YII_ENV;
if ($YII_ENV == 'dev') {
    error_reporting(E_ERROR | E_PARSE);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
} else {
    error_reporting(0);
    defined('YII_DEBUG') or define('YII_DEBUG', false);
}

require __DIR__ . '/protected/vendor/autoload.php';
require __DIR__ . '/protected/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/protected/config/web.php';

(new yii\web\Application($config))->run();
