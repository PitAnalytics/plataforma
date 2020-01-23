<?php

class editables extends Controller{

    public function indice(){

        $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cicle=$cicleModel->getLast();
        $modules=$cicle['Modules'];
        $cicleModel->detachMySql();

        $customModel = new CustomModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $custom=$customModel->table($modules);
        $customModel->detachMySql();

        echo(json_encode($custom));
    }

    public function actualizar(){

        $customModel = new CustomModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));

        if(isset($_POST['req'])){

            //decodificamos post request como array asociativo
            $postData=json_decode($_POST['req'],true);
            $customModel->update($postData);
            $customModel->detachMySql();
            $customModel=null;

            //tomamos el modelo del ciclo
            $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle=$cicleModel->getLast();
            $modules=$cicle['Modules'];
            $cicleModel->detachMySql();
            $cicleModel=null;

            //obtenemos cuentas del model de cuentas
            $accountModel=new AccountModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $accounts=$accountModel->index();
            $accountModel->detachMySql();
            $accountModel=null;

            //transcribimos
            $transcryptModel = new TranscryptModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $summary = $transcryptModel->transcrypt($accounts,$cicle);
            $transcryptModel->detachMySql();
            $transcryptModel = null;

            //escribimos los correspondientes resumenes completos
            $summaryModel = new SummaryModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $summaryModel->truncate();
            $summaryModel->write($summary);
            $summaryModel->detachMySql();
            $summaryModel = null;

            //escribimos el guardado definitivo
            $storeModel = new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $storeModel->delete($cicle);
            $storeModel->write($summary);
            $storeModel->detachMySql();
            $storeModel = null;

            $response=['status'=>'success'];

            echo(json_encode($response));
        
        }

        else{

            echo('error 402');

        }

    }

}

?>