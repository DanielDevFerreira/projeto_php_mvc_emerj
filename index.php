<?php

require __DIR__.'/vendor/autoload.php';

use \App\Http\Router;
use \App\Utils\View;

define('URL', 'http://localhost/curso_php_mvc');

// define valor padrão das varia´veis
View::init([
    'URL' => URL
]);

// inicia o router
$obRouter = new Router(URL);

//inclui as rotas de páginas
include __DIR__.'/routes/pages.php';

// imprime o response da rota
$obRouter->run()->sendResponse();
