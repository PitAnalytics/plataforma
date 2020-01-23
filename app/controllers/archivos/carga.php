<?php

class carga extends Controller{

    public function lista(){

        $loaderModel = new LoaderModel(new BigQuery('informe-211921'),new Bseg());
        $files=$loaderModel->scan('../../bseg','csv');
        echo(json_encode($files));

    }

    public function subir($file){

        $loaderModel = new LoaderModel(new BigQuery('informe-211921'),new Bseg());
        $loaderModel->load('../app/sap/headers.csv','../../bseg/'.$file.'.csv');
        $loaderModel=null;

        echo('success');

    }

}

?>