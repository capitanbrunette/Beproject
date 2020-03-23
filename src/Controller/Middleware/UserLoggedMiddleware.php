<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 19/04/2018
 * Time: 18:54
 */

namespace BeProject\Controller\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class UserLoggedMiddleware{
    public function __invoke(Request $request, Response $response, Callable $next)
    {
        if(!isset($_SESSION['user_id'])){
            //return $response->withStatus(302)->withHeader('Location', '/');
        }else{
            return $response->withStatus(302)->withHeader('Location', "/home/$_SESSION[user_id]");
        }
        return $next($request, $response);
    }
}

