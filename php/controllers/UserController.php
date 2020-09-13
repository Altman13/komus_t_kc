<?php
require "models/User.php";

use Komus\User;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Container;

class UserController
{
    private $user;
    private $ret;
    public function __construct(Container $container)
    {
        $this->user = $container['user'];
    }
    public function show(Request $request, Response $response)
    {
        try {
            $this->ret = json_decode($this->user->getAllOperators());
            if (isset($this->ret->error_text) && ($this->ret->error_text)) {
                $response->getBody()->write($this->ret);
                $this->ret = $response->withStatus(500);
            }
        } catch (\Throwable $th) {
            $this->ret['error_text'] = 'Произошла ошибка при выборе операторов ' . $th->getMessage();
            $response->getBody()->write($this->ret);
            $this->ret = $response->withStatus(500);
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }
    public function create(Request $request, Response $response)
    {
        try {
            $get_file = $request->getUploadedFiles();
            $uploaded_file = $get_file['upload_file'];
            $this->ret = $this->user->create($uploaded_file, $response);
        } catch (\Throwable $th) {
            $this->ret['error_text'] = 'Произошла ошибка при добавлении операторов ' . $th->getMessage();
            $response->getBody()->write($this->ret);
            $this->ret = $response->withStatus(500);
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }

    public function update(Request $request, Response $response)
    {
        try {
            $operator = json_decode($request->getBody());
            $this->ret = $this->user->setStOperator($operator->data);
            if ($this->ret == false) {
                $this->ret['error_text'] = 'Произошла ошибка при назначении старшего оператора ';
                $this->ret = $response->withStatus(500);
            }
        } catch (\Throwable $th) {
            $this->ret['error_text'] = 'Произошла ошибка при назначении старшего оператора ' . $th->getMessage();
            $response->getBody()->write($this->ret);
            $this->ret = $response->withStatus(500);
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }
}
