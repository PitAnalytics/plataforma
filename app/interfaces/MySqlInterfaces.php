<?php

interface MySqlConnectionInterface{

    public function attachMySql($bigQuery);
    public function detachMySql();

}

interface MySqlIndexInterface extends MySqlConnectionInterface{

    public function index();
    
}

interface MySqlOverwriteInterface extends MySqlConnectionInterface{

    public function overwrite($accounts,$modules);

}

interface MySqlTruncateInterface extends MySqlConnectionInterface{

    public function truncate();

}

interface MySqlUpdateInterface extends MySqlConnectionInterface{

    public function update($postData);

}

interface MySqlWriteInterface extends MySqlConnectionInterface{

    public function write($data);

}

interface MySqlValidateInterface extends MySqlConnectionInterface{

    public function validate($postUser);

}

?>