<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', __DIR__);
chdir(APP_ROOT);

session_start();

require_once APP_ROOT . '/app/core/Controller.php';
require_once APP_ROOT . '/app/core/Router.php';

$page = $_GET['page'] ?? null;
$action = $_GET['action'] ?? 'index';

(new Router())->dispatch($page, $action);
