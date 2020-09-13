<?php

use Komus\Calls;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Container;

class CallsController
{
    private $calls;
    private $ret;
    public function __construct(Container $container)
    {
        $this->calls = $container['calls'];
    }
    // TODO: отправка сообщения об ошибке на почту разработчикам
    public function show(Request $request, Response $response)
    {
        try {
            $this->ret = json_decode($this->calls->read());
            if (isset($this->ret->error_text) && ($this->ret->error_text)) {
                $response->getBody()->write($this->ret);
                $this->ret = $response->withStatus(500);
            }
        } catch (\Throwable $th) {
                $this->ret['error_text'] = "Произошла ошибка в CallsController " . $th->getMessage() . PHP_EOL;
                $response->getBody()->write($this->ret);
                $this->ret = $response->withStatus(500);
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }
    public function make()
    {
        //$this->calls->create();
    }
}
