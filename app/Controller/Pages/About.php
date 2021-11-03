<?php

namespace App\Controller\Pages;
use \App\Utils\View;

class About extends Page{

    /**
     * Método responsável para retornar todo o conteúdo (view) da about
     * @return string
     */
    public static function getAbout(){
        //view da home
        $content =  View::render('pages/about', [
            'name' => 'Projeto PHP MVC',
            'description' => 'Projeto de estudo'
        ]);

        // retorna a view da página
        return parent::getPage('Sobre', $content);
    }
}