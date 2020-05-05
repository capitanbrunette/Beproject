<?php
/**
 * Created by PhpStorm.
 * User: abrunet
 */

namespace BeProject\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;

class ConfigurationController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $arguments) {

        if (isset($_SESSION['user_id'])) {

            if ($_SESSION['user_id'] == $arguments['user_id']) {
                $service = $this->container->get('configuration');
                $row = $service($_SESSION['user_id']);

                return $this->container->get('View')->render($response, 'settings.twig', [
                        'name' => $row['name'],
                        'username' => $row['username'],
                        'email' => $row['email'],
                        'id' => $_SESSION['user_id'],
                        'userId' => $row['userId'],
                        'createdAt' => $row['createdAt'],
                        ]
                );

            } else {
                return $response->withStatus(403)->withHeader('Location', "/");
            }

        }else{ return $response->withStatus(403)->withHeader('Location', "/");}


    }


    public function deleteAccount(Request $request, Response $response, array $arguments)
    {

        if ($_SESSION['user_id'] == $arguments['user_id']) {


            $service = $this->container->get('delete');
            $service($_SESSION['user_id']);
            $_SESSION['user_id']= null;
            return $response->withStatus(302)->withHeader('Location', "/");
        }


        return $response->withStatus(302)->withHeader('Location', "/user");




    }
}
