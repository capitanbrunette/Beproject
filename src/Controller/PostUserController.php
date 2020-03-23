<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 18/04/2018
 * Time: 17:47
 */
namespace BeProject\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;



class postUserController{
    protected $container;



    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /*public function indexAction(Request $request, Response $response) {
        $messages = $this->container->get('flash')->getMessages();

        $userRegistredMessages = isset($messages['user_register']) ? $messages['user_register']: [];
        return $this->container->get('View')
            ->render($response,'register.twig', ['messages' => $userRegistredMessages]);
    }*/

    public function __invoke(Request $request, Response $response) {
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post_user_service');
            $validatestring = md5(uniqid());
            $service($data, $validatestring);
            //$this->container->get('flash')->addMessage('user_register','User successfully registred');

            //$mailer = $this->container->get('mail');

            $email = $data['email'];
            $username = $data['username'];


            $directory = '/home/vagrant/code/beproject/public/uploads/';
            mkdir($directory.$data['username'],0777,true);
            $imageDirectory = '/home/vagrant/code/beproject/public/assets/images/';
            copy ( $imageDirectory."profile.jpg", $imageDirectory."/profiles/".$data['username'].".jpg" );

            //$data ['response'] = 'OK';
            $dataa = array('response'=>'OK');
            //$mailer($email, $username, $validatestring);
            return $response->withStatus(200)->withHeader("Content-type", "application/json")->withJson($dataa);
        }catch (\Exception $e){
            $dataa = array('response'=>'KO', 'message'=>$e->getMessage());
            return $response->withStatus(200)->withHeader("Content-type", "application/json")->withJson($dataa);
        }

    }


}
