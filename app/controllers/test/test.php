<?php

// prueba de aplicacion
// .chechar archivos .htaccess y configuracion de servidor apache
class test extends Controller{

    public function index(){

        echo('BIENVENIDO A LA PLATAFORMA MULTIVA<br>'.'2019');

    }
    
    public function tester($params){
        
        echo($params);
    
    }

    public function config(){

        echo("Host :".HOST."<br>");
        echo("User :".USER."<br>");
        echo("Password :".PASSWORD."<br>");
        echo("Database :".DATABASE."<br>");
        
    }

}

?>