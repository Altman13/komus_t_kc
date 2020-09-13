<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Container;

class LoginController
{
    private $login;
    private $ret;
    public function __construct(Container $container)
    {
        $this->login = $container['login'];
    }
    //TODO : добавить счетчик неудачных попыток входа
    public function inter(Request $request, Response $response)
    {
            try {
                $user_data = json_decode($request->getBody());
                $this->ret = $this->login->sign($user_data->data->userpassword, $user_data->data->username);
                if (isset($this->ret->error_text) && ($this->ret->error_text)) {
                    $response->getBody()->write($this->ret);
                    $this->ret = $response->withStatus(500);
                }
            } catch (\Throwable $th) {
                $this->ret['error_text'] = "Произошла ошибка при попытке входа " . $th->getMessage() . PHP_EOL;
                $response->getBody()->write($this->ret);
                $this->ret = $response->withStatus(500);
            }
            return $this->ret = json_encode($this->ret, JSON_UNESCAPED_UNICODE);
        }
}
