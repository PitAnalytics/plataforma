<?php

//clase controladora de carga y ejecucion de modelos

class Controller{

    public function __construct(){

        //header('Content-type: application/json');

    }

    // mandamos a llamar un modelo
    public function model($model){

        // creamos ruta a partir de parametros
        $route='../app/models/'.$model.'.php';

        // de existir archivo con clase; lo abrimos y mandamos llamar
        if(file_exists($route)){

            require_once($route);

        }

        //
        else{

            echo("error 404");

        }

    }

    private function loadDefault(){

        require_once('..app/interfaces/BigQueryInterfaces.php');
        require_once('..app/interfaces/MySqlInterfaces.php');

    }

}

?>