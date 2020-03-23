<?php


namespace BeProject\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;

class SessionController{
    protected $container;
    public function __construct(ContainerInterface $container ) {
        $this->container = $container;
    }

    public function setUser(Request $request, Response $response) {
        try {
            $data = $request->getParsedBody();

            $service = $this->container->get('signin');
            $username = $service($data);
            $_SESSION['user_id']= "$username";

            //AFEGIR COMPROVACIONS



            $data = array('response'=>'OK');
            return $response->withStatus(200)->withHeader("Content-type", "application/json")->withJson($data);
        }catch (\Exception  $exception){
            $data = array('response'=>'KO');
            return $response->withStatus(200)->withHeader("Content-type", "application/json")->withJson($data);
        }
    }


    public function unsetUser(Request $request, Response $response){
        $_SESSION['user_id']= null;

        return $response->withStatus(302)->withHeader('Location', "/");
    }
}