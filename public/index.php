<?php
use Core\Autoload;
use Core\Router;
use Core\Error;
use Core\Functions;
require_once '../Core/Autoload.php';

// require '../vendor/autoload.php';
Autoload::class();
Error::setError();
require_once "../Routing/Web.php";
Router::dispatch();


