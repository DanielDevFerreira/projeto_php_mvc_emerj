<?php 

namespace App\Http;

class Response {
    
    /**
     * Código do Status HTTP
     * @var integer
     */
    private $httpCode;

    /**
     * Cabeçalho do Response
     */
    private $headers = [];

    /**
     * Tipo de conteúdo que está sendo retornado
     */
    private $contentType = 'text/html';

    /**
     * Conteúdo do Response
     * @var mixed
     */
    private $content;

    /**
     * Método responsabel por inciar e classe e definir os valores
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html'){
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }
    

    /**
     * Método responsável por alterar o content type do response
     * @param string $contentType
     */
    public function setContentType($contentType){
        $this->$contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adicionar um registro no cabeçalho do response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar os headers para o navegador
     */
    private function sendHeaders(){
        //status
        http_response_code($this->httpCode);

        //enviar headers
        foreach ($this->headers as $key => $value) {
            header($key.':'.$value);
        }
    }

    /**
     * Método responsavel por enviar a resposta para o usuário
     */
    public function sendResponse(){
        //enviar os headers
        $this->sendHeaders();
        switch ($this->contentType) {
            case 'text/html':
              echo $this->content;
            exit;
        }
    }
}