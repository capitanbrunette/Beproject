<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 04/01/2020
 * Time: 0:39
 */

namespace BeProject\Controller;


class ConfigurationController
{
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }


    public function __invoke(Request $request, Response $response, array $arguments) {
        $user = $_SESSION['user_id'];
        //$service = $this->container->get('show_notifications');
        //$notifications = $service($user);

        return $this->container->get('View')->render($response, 'configuration.twig',['id' => $user]); //, 'notifications' => $notifications]);
    }


}