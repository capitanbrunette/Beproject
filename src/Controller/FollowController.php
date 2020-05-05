<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 04/05/2020
 * Time: 10:00
 */

namespace BeProject\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;


class FollowController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $arguments) {
        if($_SESSION['user_id'] != $arguments['username']) {
            $service = $this->container->get('follow');
            $service($_SESSION['user_id'],$arguments['username'], true);

            return $response->withStatus(302)->withHeader('Location', $_SERVER['HTTP_REFERER']);
        }
    }

    public function unfollow(Request $request, Response $response, array $arguments) {
        if($_SESSION['user_id'] != $arguments['username']) {
            $service = $this->container->get('follow');
            $service($_SESSION['user_id'],$arguments['username'], false);

            return $response->withStatus(302)->withHeader('Location', $_SERVER['HTTP_REFERER']);
        }
    }

}