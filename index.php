<?php

require __DIR__.'/vendor/autoload.php';

use \App\Controller\Pages\Home;
use \App\Http\Router;

define('URL', 'http://localhost/curso_php_mvc');

$obRouter = new Router(URL);

echo '<pre>';
print_r($obRouter);

echo Home::getHome();