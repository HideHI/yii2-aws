<?php
// This is global bootstrap for autoloading
// Seems wrong for me to have to go back so many parent directories to properly boostrap
// yii from the tests directory.
require(__DIR__ . '/../../../../vendor/autoload.php');
require(__DIR__ . '/../../../../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../../../../config/web.php');

(new yii\web\Application($config));