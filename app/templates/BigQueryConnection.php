<?php

class BigQueryConnection implements BigQueryConnectionInterface{

    protected $bigQuery;

    public function attachBigQuery($bigQuery){

        $this->bigQuery=$bigQuery;

    }

    public function detachBigQuery(){

        $this->bigQuery=null;
    
    }

}

?>