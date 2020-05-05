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
                $service = $this->container->get('profile');
                $data = $service($arguments['user_id']);
                if($_SESSION['user_id'] == $arguments['user_id']) {
                    $isadmin = true;
                }else{
                    $isadmin = false;
                }

                return $this->container->get('View')->render($response, 'profile.twig',
                    [
                        'id' => $_SESSION['user_id'],
                        'user'=>$data['user'],
                        'followers'=>$data['followers'],
                        'following'=>$data['following'],
                        'projects'=>$data['projects'],
                        'collabs'=>$data['collabs'],
                        'knowledge'=>$data['knowledge'],
                        'isadmin'=>$isadmin
                    ]
                );

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
