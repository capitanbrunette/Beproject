<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 18/05/2018
 * Time: 2:16
 */



namespace Pwbox\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;


class NotificationController{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $arguments) {
        $user = $_SESSION['user_id'];
        //$service = $this->container->get('show_notifications');
        //$notifications = $service($user);

        return $this->container->get('View')->render($response, 'notifications.twig',['id' => $user]); //, 'notifications' => $notifications]);
    }

}

