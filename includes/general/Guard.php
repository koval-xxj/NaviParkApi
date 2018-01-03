<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Gn;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of Guard
 *
 * @author SERHIO
 */
class Guard extends \Slim\Csrf\Guard {
  
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
      return $next($request, $response);
    }
  
  
}
