<?php
// This is global bootstrap for autoloading
// This seems incorrect to have to load these classes with so many parent directories
require_once(__DIR__ . '/../../../../vendor/autoload.php');
require_once(__DIR__ . '/../../../../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../../../../config/web.php');

(new yii\web\Application($config));
