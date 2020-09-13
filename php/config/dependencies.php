<?php
use Komus\Login;
use Komus\Base;
use Komus\Calls;
use Komus\Contact;
use Komus\MailLog;
use Komus\Region;
use Komus\Report;
use Komus\TimeZone;
use Komus\User;
use Komus\UserGroup;

require_once "config.php";
require_once "vendor/autoload.php";

$container = $app->getContainer();
$container['pdo'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    $pdo = new PDO(
        "mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'],
        $settings['user'],
        $settings['pass'],
        $options
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['login'] = function ($c) {
    $login = new Login($c['pdo']);
    return $login;
};
$container['base'] = function ($c) {
    $base = new Base($c['pdo']);
    return $base;
};
$container['user'] = function ($c) {
    $user = new User($c['pdo']);
    return $user;
};
$container['calls'] = function ($c) {
    $calls = new Calls($c['pdo']);
    return $calls;
};
$container['contact'] = function ($c) {
    $contact = new Contact($c['pdo']);
    return $contact;
};
$container['mailLog'] = function ($c) {
    $mailLog = new MailLog($c['pdo']);
    return $mailLog;
};
$container['region'] = function ($c) {
    $region = new Region($c['pdo']);
    return $region;
};
$container['report'] = function ($c) {
    $report = new Report($c['pdo']);
    return $report;
};
$container['timeZone'] = function ($c) {
    $timeZone = new TimeZone($c['pdo']);
    return $timeZone;
};
$container['userGroup'] = function ($c) {
    $userGroup = new UserGroup($c['pdo']);
    return $userGroup;
};
