<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 19/12/2019
 * Time: 18:39
 */


require '../vendor/autoload.php';

$settings = require  __DIR__.'/../app/settings.php';
$app = new  \Slim\App($settings);

require __DIR__.'/../app/routes.php';
require __DIR__.'/../app/dependencies.php';

$app->run();


