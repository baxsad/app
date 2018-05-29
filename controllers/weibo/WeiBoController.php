<?php

namespace Buff\controllers\weibo;

class WeiBoController 
{
	protected $container;

    public function __construct(ContainerInterface $container) {
       $this->container = $container;
    }

    public function home(Request $req,  Response $res, $args = []) {
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type',' text/html')
            ->write('');
    }
}