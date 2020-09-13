<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Container;

class BaseController
{
    private $base;
    private $ret;
    public function __construct(Container $container)
    {
        $this->base = $container['base'];
    }
    public function upload(Request $request, Response $response)
    {
        try {
            $get_file = $request->getUploadedFiles();
            $uploaded_file = $get_file['upload_file'];
            $this->ret = json_decode($this->base->create($uploaded_file));
            if (isset($this->ret->error_text) && ($this->ret->error_text)) {
                $response->getBody()->write($this->ret);
                $this->ret = $response->withStatus(500);
            }
        } catch (\Throwable $th) {
            $this->ret['error_text'] = "Произошла ошибка в BaseController " . $th->getMessage() . PHP_EOL;
            $response->getBody()->write($this->ret);
            $this->ret = $response->withStatus(500);
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }
}
