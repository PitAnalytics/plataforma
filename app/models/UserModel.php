<?php

class UserModel extends MySqlConnection implements MySqlValidateInterface{

    //inyectamos dependencia de lib desde el constructor
    public function __construct($mySql){

        $this->attachMySql($mySql);

    }

    //validacion de usuario a nivel sql
    public function validate($postUser){

        //validamos password en base de datos
        $validation = $this->mySql->validatePassword("Users","Nickname","Password",$postUser['user'],$postUser['password']);
        $this->detachMySql();

        //retornamos verdadero en caso de obtener un 2 en validacion
        if($validation==2){

            return true;

        }

        else{

            return false;

        }

    }

}

?>