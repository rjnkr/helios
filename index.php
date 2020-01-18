<?php

// Gebruik het slim framework
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/ext/vendor/autoload.php';

include('include/config.php');
include('include/functions.php');
include('include/startadmin.php');

// Slime framework doet het werk voor ons
$app = AppFactory::create();

// Laat alle JSON/REST webservices
$files = glob("routes/route.*.php");
foreach($files as $file) {
//  Debug(__FILE__, __LINE__, sprintf("include %s", $file));
  include($file);
}

// De definitie zijn geladen. Uitvoeren van de code
$app->run();


?>