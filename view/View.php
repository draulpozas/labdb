<?php

require_once __DIR__."/../config/autoload.php";

class View{
    public static function infoPage($title, $text){
        $html = file_get_contents('templates/infoPage.html');
        $html = strtr($html, ['{{title}}' => $title, '{{text}}' => $text]);
        $html = self::addHeader($html);
        // TO-DO language replacing
        echo $html;
    }

    public static function newReagent(){
        $html = file_get_contents('templates/newReagent.html');
        $html = self::addHeader($html);
        // TO-DO language replacing
        echo $html;
    }

    public static function searchReagent($replace){
        $html = file_get_contents('templates/infoPage.html');
        $html = strtr($html, $replace);
        $html = self::addHeader($html);
        // TO-DO language replacing
        echo $html;
    }

    public static function editReagent($replace){
        $html = file_get_contents('templates/editReagent.html');
        $html = strtr($html, $replace);
        $html = self::addHeader($html);
        // TO-DO language replacing
        echo $html;
    }
}
 ?>