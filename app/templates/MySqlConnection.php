<?php

class MySqlConnection implements MySqlConnectionInterface{

    protected $mySql;

    public function attachMySql($mySql){

        $this->mySql=$mySql;

    }

    public function detachMySql(){

        $this->mySql=null;
    
    }
    
}

?>