<?php 

namespace App\Utils;

class View {

    /**
     * Variáveis padrões da View
     * @var array
     */
    private static $vars = [];

    /**
     * Método responsável por definir os dados inicias da classe
     * @param array $vars
     */
    public static function init($vars = []){
        self::$vars = $vars;
    }

    /**
     * Método responsável por retornar o conteúdo de uma view
     * @param string
     * @return string
     */
    private static function getContentView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        //verifica se o arquivo existe, se existir ? pegue o arquivo se não retorne vazio
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável para retorna o conteúdo renderizado da uma view 
     * @param string
     * @param array $vars(string/numeric)
     * @return string
     */
    public static function render($view, $vars = []){
        // CONTEÚDO DA VIEW
        //self é utilizada para acessar membros estáticos
        $contentView = self::getContentView($view);

        //merge de variáveis da view
        $vars = array_merge(self::$vars, $vars);

        // chaves do array de variaveis
        $keys = array_keys($vars);
        //mapeando as chaves do array, para pegar os valores
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        },$keys);

        // retorna o conteúdo renderizado
        return str_replace($keys, array_values($vars), $contentView);
    }
}