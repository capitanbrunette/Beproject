<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 19/12/2019
 * Time: 18:55
 */

$app->add('BeProject\Controller\Middleware\SessionMiddleware');

//FUNCIONALITATS GET


$app->get(
    '/',
    'BeProject\Controller\MasterController'
)->add('BeProject\Controller\Middleware\UserLoggedMiddleware');

$app->get(
    '/home/{user_id}',
    'BeProject\Controller\HomeController'
);

$app->get(
    '/logout',
    'BeProject\Controller\SessionController:unsetUser'
);

$app->get(
    '/configuration/{user_id}',
    'BeProject\Controller\ConfigurationController'
);

$app->get(
    '/profile/{user_id}',
    'BeProject\Controller\ProfileController'
);

$app->get(
    '/delete/{user_id}',
    'BeProject\Controller\ConfigurationController:deleteAccount'
);

$app->get(
    '/{user}/notifications',
    'BeProject\Controller\NotificationController'
);


//FUNCIONALITATS POST

$app->post(
    '/user',
    'BeProject\Controller\PostUserController'
);

$app->post(
    '/signin',
    'BeProject\Controller\SessionController:setUser'
);