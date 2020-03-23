<?php
/**
 * Created by PhpStorm.
 * User: abrunet
 */

namespace BeProject\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;

class ProfileController
{
    protected $container;
    private $user;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    



    public function __invoke(Request $request, Response $response, array $arguments) {

        if (isset($_SESSION['user_id'])) {


            if ($_SESSION['user_id'] == $arguments['user_id']) {
                /*$service = $this->container->get('profile');
                $row = $service($_SESSION['user_id']);*/


                //$total_storage = 1073741824;
                return $this->container->get('View')->render($response, 'profile.twig',
                    [
                        'id' => $_SESSION['user_id']//, 'folders'=>$data['folders'], 'files'=>$data['files'], 'path'=>$arguments['folder'], 'isadmin'=>$data['isadmin']
                    ]

                /*[
                        'name' => $row['nom'],
                        'username' => $row['username'],
                        'email' => $row['email'],
                        'id' => $_SESSION['user_id'],
                        'birthdate' => $row['birthdate'],
                        'activated' => $row['activated'],
                        'validation' => $row['validation'],
                        'spent_storage' => $row['storage'],
                        'total_storage' => $total_storage,
                        'percent_storage' => $row['storage']*100/$total_storage]*/

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
