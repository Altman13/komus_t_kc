<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './controllers/ApiController.php';
require './controllers/UserController.php';
require './controllers/LoginController.php';
require './controllers/CallsController.php';
require './controllers/ReportController.php';
require './controllers/BaseController.php';
require './controllers/ContactController.php';

require_once './config/dependencies.php';

//$app = new \Slim\App;
// $app->add(new Tuupola\Middleware\JwtAuthentication([
//      "path" => "/api", /* or ["/api", "/admin"] */
//     "secret" => getenv("JWT_SECRET")
// ]));

$app->post('/api/base', BaseController::class . ':upload');
$app->post('/api/login', LoginController::class . ':inter');
$app->get('/api/login', LoginController::class . ':inter')
            ->add(new \DavidePastore\Slim\Validation\Validation($validators));
$app->post('/api/calls', CallsController::class . ':make');
$app->get('/api/calls', CallsController::class . ':show');
$app->post('/api/user', UserController::class . ':create');
$app->get('/api/user', UserController::class . ':show');
$app->patch('/api/user', UserController::class . ':update');
$app->get('/api/report', ReportController::class . ':show');
$app->post('/api/contact', ContactController::class . ':update');
$app->patch('/api/contact', ContactController::class . ':unlock');
$app->get('/api/contact', ContactController::class . ':getContactRusInfo');

//xml, json, html, 
$app->get('/api/get', ApiController::class . ':show');
$app->post('/api/post', ApiController::class . ':create');

$app->post('/api/mail', MailController::class . ':send');
$app->run();
