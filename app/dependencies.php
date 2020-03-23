<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 20/12/2019
 * Time: 0:47
 */

$container = $app->getContainer();
$container['View'] = function ($container){
    $view = new \Slim\Views\Twig ('../src/View', [
        //'cache' => '../var/cache'
    ]);

    $basePath = rtrim(str_ireplace('index.php', '',$container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\Twigextension($container['router'], $basePath));

    return $view;
};

$container['doctrine'] = function($container) {
    $config = new \Doctrine\DBAL\Configuration();
    $conn = \Doctrine\DBAL\DriverManager::getConnection(
        $container->get('settings')['database'],
        $config
    );

    return $conn;
};

$container['repository'] = function($container) {
    $repository = new BeProject\Model\Implementation\DoctrineRepository(
        $container->get('doctrine')
    );
    return $repository;
};


$container['post_user_service'] = function($container) {
    $useCase = new BeProject\Model\UseCase\PostUserUseCase(
        $container->get('repository')

    );
    return $useCase;
};


$container['signin'] = function ($container){
    $user_signed = new BeProject\Model\UseCase\SignUserIn($container->get('repository'));
    return $user_signed;
};



