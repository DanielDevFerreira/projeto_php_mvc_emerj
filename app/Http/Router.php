<?php

namespace App\Http;
use \Closure;
use \Exception;
use \ReflectionFunction;

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
      * método responsável por adicionar uma rota na classe
      *@param string $method
      *@param string $route
      *@param array $params
      */
     private function addRoute($method, $route, $params = []){
        // validação dos parâmetros
        foreach ($params as $key => $value) {
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //variáveis da rota
        $params['variables'] = [];

        //padrão de validação das variáveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        //padrão de validação da URL
        $patternRoute = '/^'.str_replace('/','\/', $route).'$/';
        
        //adiciona a rota da classe
        $this->routes[$patternRoute][$method] = $params;
        
     }

     /**
      * Método responsável por definir uma rota de GET
      *@param string $route
      *@param string $param
      */
     public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
     }
    
     /**
      * Método responsável por definir uma rota de POST
      *@param string $route
      *@param string $param
      */
     public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
     }
     
     /**
      * Método responsável por definir uma rota de PUT
      *@param string $route
      *@param string $param
      */
     public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
     }
    
     /**
      * Método responsável por definir uma rota de DELETE
      *@param string $route
      *@param string $param
      */
     public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
     }

     /**
      * Método responsável por retornar a URI desconsiderando o prefixo
      *@return string
      */
     private function getUri(){
        // URI da request
        $uri = $this->request->getUri();

        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];

        //retorna a URI sem prefixo
        return end($xUri);
     }

     /**
      * Método responsável por retornar os dados da rota atual
      *@return array
      */
     private function getRoute(){
        //URI
        $uri = $this->getUri();

        //method
        $httpMethod = $this->request->getHttpMethod();

        //valida as rotas
        foreach ($this->routes as $patternRoute => $methods) {
            //verifica se a rota bate o padrão
            if(preg_match($patternRoute, $uri, $matches)){
               //verifica o método
                if(isset($methods[$httpMethod])){
                    //remove a primeira posição
                    unset($matches[0]);

                    //variaveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;
                 

                    //retorno dos parâmetros da rota
                    return $methods[$httpMethod];
                }
                // método não permitido/definido
                throw new Exception("Método não permitido", 405);
            }
        }

        //URL não encontrada
        throw new Exception("URL não encontrada", 404);
    }

     /**
      * Método responsável por executar a rota atual
      */
     public function run(){
        try{
            // obtém a rota atual
            $route = $this->getRoute();
            
            //verifica o controlador
            if(!isset($route['controller'])){
                throw new Exception("A URL não pôde ser processada", 500);
            }

            //argumentos da função
            $args = [];

            //reflection
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            // retorna a execução da função
            return call_user_func_array($route['controller'], $args);
        }catch(Exception $e){
            return new Response($e->getCode(), $e->getMessage());
        }
     }
}