<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 23/04/2018
 * Time: 5:18
 */

namespace BeProject\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;


class homeController{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $arguments) {

        if($_SESSION['user_id'] == $arguments['user_id']){

            /*if(!isset($arguments['folder'])){
                $arguments['folder']='';
            }*/


            $service = $this->container->get('home_content');
            $data = $service();
            /*$data = $service($arguments['folder'],$arguments['user_id']);
            $arguments['folder']='/'.$arguments['folder'];*/

            return $this->container->get('View')->render($response, 'home.twig',
                [
                    'id' => $_SESSION['user_id'],
                    'projects'=>$data['projects'],
                    'profiles'=>$data['profiles'],
                    'tags'=>$data['tags'],
                    'locations'=>$data['locations']
                    //, 'files'=>$data['files'], 'path'=>$arguments['folder'], 'isadmin'=>$data['isadmin']
                ]);
        }else{
            return $response->withStatus(302)->withHeader('Location', "/");

        }

    }

    public function indexAction(Request $request, Response $response) {
        return $this->container->get('View')->render($response, 'home.twig');
    }
}

