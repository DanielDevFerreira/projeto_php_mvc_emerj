<?php

namespace App\Http;

class Router {

    /**
     * URL completa do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * índice de rotas
     * @var array
     */
    private $routes = [];
    
    /**
     * Instancia de Request
     * @var Request
     */
     private $request;


     /**
      * método responsável por inciar a classe
      *@param string $url
      */
     public function __construct($url){
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
     }

     /**
      * Método responsável por definir o prefixo das rotas
      */
     private function setPrefix(){
         //informações da URL atual
         $parseURL = parse_url($this->url);

         // define o prefixo
         $this->prefix = $parseURL['path'] ?? '';
     }



     /**
      * Método responsável por definir uma rota de GET
      *@param string $route
      *@param string $param
      */
     public function get($route, $params = []){

     }

}