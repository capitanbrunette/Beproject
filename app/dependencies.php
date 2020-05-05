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



/*USER SIGN UP*/

$container['post_user_service'] = function($container) {
    $useCase = new BeProject\Model\UseCase\PostUserUseCase(
        $container->get('repository')

    );
    return $useCase;
};

/*USER SIGN IN*/

$container['signin'] = function ($container){
    $user_signed = new BeProject\Model\UseCase\SignUserIn($container->get('repository'));
    return $user_signed;
};


$container['configuration'] = function ($container){
    $user_settings = new BeProject\Model\UseCase\GetSettingsUseCase($container->get('repository'));
    return $user_settings;
};

$container['profile'] = function ($container){
    $user_profile = new BeProject\Model\UseCase\GetProfileUseCase($container->get('repository'));
    return $user_profile;
};

$container['home_content'] = function ($container){
    $home_content = new BeProject\Model\UseCase\GetHomeContentUseCase($container->get('repository'));
    return $home_content;
};


$container['delete'] = function ($container){
    $user_to_delete = new BeProject\Model\UseCase\DeleteUserUseCase($container->get('repository'));
    return $user_to_delete;
};


/********** PROJECTS **********/

$container['post_project'] = function($container) {
    $useCase = new BeProject\Model\UseCase\PostProjectUseCase($container->get('repository'));
    return $useCase;
};

$container['project_content'] = function($container) {
    $useCase = new BeProject\Model\UseCase\GetProjectUseCase($container->get('repository'));
    return $useCase;
};


/********* FOLLOW ********/
$container['follow'] = function($container) {
    $useCase = new BeProject\Model\UseCase\FollowUseCase($container->get('repository'));
    return $useCase;
};





