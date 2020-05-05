<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 04/05/2020
 * Time: 8:53
 */

namespace BeProject\Controller;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;

class ProjectController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $arguments) {

        if (isset($_SESSION['user_id'])) {

            if(!isset($arguments['project'])){
                $arguments['project']='';
            }

            $service = $this->container->get('project_content');
            $data = $service($_SESSION['user_id'], $arguments['owner'],$arguments['project']);
            $arguments['project']='/'.$arguments['project'];

            return $this->container->get('View')->render($response, 'project.twig', [

                'id' => $_SESSION['user_id'],
                'project'=>$data['project'],
                'subprojects'=>$data['projects'],
                //'files'=>$data['files'],
                'path'=>$arguments['project'],
                'isadmin'=>$data['isadmin']

            ]);





        }else{ return $response->withStatus(403)->withHeader('Location', "/");}


    }

}