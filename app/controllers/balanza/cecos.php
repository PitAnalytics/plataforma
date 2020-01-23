<?php

class cecos extends Controller{

    //
    public function indice(){

        //imprimimos todos los centros de costo
        $cecosModel = new cecosModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cecos = $cecosModel->index();
        $cecosModel->detachMySql();
        echo(json_encode($cecos));

    }
    

    //
    public function buscar($module,$ceco){

        $cecosModel = new cecosModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cecos = $cecosModel->search($module,$ceco);
        $cecosModel->detachMySql();
        $cecosModel=null;

        echo(json_encode($cecos));

    }


    //
    public function agregar(){

        if(isset($_POST['req'])){


            //datos de peticion en json
            $postData=json_decode($_POST['req'],true);

            //actualizamos centro de costo
            $cecosModel = new cecosModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cecosModel->add($postData);
            $cecosModel->detachMySql();
            $cecosModel=null;

            //borramos tablas dependientes
            $summaryModel = new SummaryModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $summaryModel->truncate();
            $summaryModel->detachMySql();
            $summaryModel = null;

            //borramos reporte
            $reportModel=new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $reportModel->truncate();
            $reportModel->detachMySql();
            $reportModel=null;

            //llamamos ciclo
            $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle=$cicleModel->getLast();
            $cicleModel->detachMySql();
            $cicleModel=null;

            //escribimos el guardado definitivo
            $storeModel = new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $storeModel->delete($cicle);
            $storeModel->detachMySql();
            $storeModel = null;

            //imprimimos el echo
            echo('success');

        }

    }


    //
    public function actualizar(){

        if(isset($_POST['req'])){

            //datos de peticion en json
            $postData=json_decode($_POST['req'],true);

            //
            $cecosModel = new cecosModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cecosModel->update($postData);
            $cecosModel->detachMySql();
            $cecosModel=null;

            //borramos tablas dependientes
            $summaryModel = new SummaryModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $summaryModel->truncate();
            $summaryModel->detachMySql();
            $summaryModel = null;

            //borramos reporte
            $reportModel=new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $reportModel->truncate();
            $reportModel->detachMySql();
            $reportModel=null;

            //llamamos ciclo
            $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle=$cicleModel->getLast();
            $cicleModel->detachMySql();
            $cicleModel=null;

            //escribimos el guardado definitivo
            $storeModel = new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $storeModel->delete($cicle);
            $storeModel->detachMySql();
            $storeModel = null;

        }

    }

    //
    public function eliminar(){

        if(isset($_POST['req'])){

            $postData=json_decode($_POST['req'],true);

            $cecosModel = new cecosModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cecosModel->delete($postData);
            $cecosModel->detachMySql();
            $cecosModel=null;

            //borramos tablas dependientes
            $summaryModel = new SummaryModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $summaryModel->truncate();
            $summaryModel->detachMySql();
            $summaryModel = null;

            //borramos reporte
            $reportModel=new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $reportModel->truncate();
            $reportModel->detachMySql();
            $reportModel=null;

            //llamamos ciclo
            $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle=$cicleModel->getLast();
            $cicleModel->detachMySql();
            $cicleModel=null;

            //escribimos el guardado definitivo
            $storeModel = new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $storeModel->delete($cicle);
            $storeModel->detachMySql();
            $storeModel = null;

        }

    }



}


?>