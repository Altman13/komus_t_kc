<?php

use Komus\Contact;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Container;

class ContactController
{
    private $contact;
    private $ret;

    public function __construct(Container $container)
    {
        $this->contact = $container['contact'];
    }
    public function show(Request $request, Response $response)
    {
        try {
            $this->ret = $this->contact->read();
            if (isset($this->ret->error_text) && ($this->ret->error_text)) {
                $response->getBody()->write($this->ret);
                $this->ret = $response->withStatus(500);
            }
        } catch (\Throwable $th) {
            $this->ret['error_text'] = "Произошла ошибка при чтении контактов " . $th->getMessage() . PHP_EOL;
            $response->getBody()->write($this->ret);
            $this->ret = $response->withStatus(500);
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }

    public function update(Request $request, Response $response)
    {
        try {
            $call = json_decode($request->getBody());
            $this->ret = $this->contact->updateStatusCall($call);
            if (isset($this->ret->error_text) && ($this->ret->error_text)) {
                $response->getBody()->write($this->ret);
                $this->ret = $response->withStatus(500);
            }
        } catch (\Throwable $th) {
            $this->ret['error_text'] = "Произошла ошибка при добавлении результата звонка " . $th->getMessage() . PHP_EOL;
            $response->getBody()->write($this->ret);
            $this->ret = $response->withStatus(500);
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }
    public function unlock(Request $request, Response $response)
    {
        try {
            $contacts = json_decode($request->getBody());
            $this->ret = $this->contact->unlockContact($contacts);
            if (isset($this->ret->error_text) && ($this->ret->error_text)) {
                $response->getBody()->write($this->ret);
                $this->ret = $response->withStatus(500);
            }
        } catch (\Throwable $th) {
            $this->ret['error_text'] = "Произошла ошибка при разблокировке контаков " . $th->getMessage() . PHP_EOL;
            $response->getBody()->write($this->ret);
            $this->ret = $response->withStatus(500);
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }
    public function getContactRusInfo(Request $request, Response $response)
    {
        try {
            $fn = 'columns_name.json';
            $this->ret = file_get_contents($fn);
        } catch (\Throwable $th) {
            $this->ret['error_text'] = "Произошла ошибка при чтении файла $fn " . $th->getMessage() . PHP_EOL;
            $response->getBody()->write(json_encode($this->ret, JSON_UNESCAPED_UNICODE));
            $this->ret = $response->withStatus(500);
        }
        return $this->ret;
    }
}
