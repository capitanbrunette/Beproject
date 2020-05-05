<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 03/05/2020
 * Time: 22:31
 */

namespace BeProject\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;


class PostProjectController
{
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $arguments) {
        if(!isset($arguments['project'])){
            $arguments['project']='';
        }
        $data = $request->getParsedBody();
        $path = $arguments['project'];
        $user_id = $_SESSION['user_id'];
        $service = $this->container->get('post_project');
        $service($data,$path,$user_id, $arguments['owner']);

        /*if($user_id != $arguments['owner']){
            $notificator = $this->container->get('notification');
            $message = $user_id." has created the folder ".$data['folder_name']." inside your folder /".$path;
            $notificator($arguments['owner'], $message);
        }*/

        return $response->withStatus(302)->withHeader('Location', $_SERVER['HTTP_REFERER']);
    }
}