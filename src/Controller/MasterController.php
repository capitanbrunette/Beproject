<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 */

namespace BeProject\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Container\ContainerInterface;



class masterController{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response) {
        
        return $this->container->get('View')->render($response, 'master.twig');
}

    public function indexAction(Request $request, Response $response) {
        return $this->container->get('View')->render($response, 'master.twig');
    }
}

