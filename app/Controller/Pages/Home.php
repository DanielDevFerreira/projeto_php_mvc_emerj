<?php

namespace App\Controller\Pages;
use \App\Utils\View;

class Home extends Page{

    /**
     * Método responsável para retornar todo o conteúdo (view) da home
     * @return string
     */
    public static function getHome(){
        //view da home
        $content =  View::render('pages/home', [
            'name' => 'Projeto PHP MVC',
            'description' => 'Projeto de estudo'
        ]);

        // retorna a view da página
        return parent::getPage('EMERJ - Sistema de controle de demandas', $content);
    }
}