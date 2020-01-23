<?php

class ciclos extends Controller{

    public function indice(){

        $cicleModel=new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cicles=$cicleModel->index();
        $cicleModel->detachMySql();
        $cicleModel = null;
        echo(json_encode($cicles));

    }

    public function regresar(){

        //activamos nuevo ciclo
        $cicleModel=new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cicle=$cicleModel->getLast();
        $cicleModel->rewind();
        $cicleModel->detachMySql();
        $ciclemodel=null;
        //truncamos tabla de Report
        $reportModel= new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $reportModel->truncate();
        $reportModel->detachMySql();
        $reportModel=null;
        //truncamos tabla de Editable
        $summaryModel= new CustomModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $summaryModel->truncate();
        $summaryModel->detachMySql();
        $summaryModel=null;
        //borramos el ultimo mes
        $storeModel = new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $storeModel->delete($cicle);
        $storeModel->detachMySql();
        $storeModel = null;
        echo("success");
                
    }

    //nuevo ciclo
    public function nuevo(){

        $cicleModel=new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cicleModel->forward();
        $cicleModel->detachMySql();
        $ciclemodel=null;
        //truncamos tabla de Report
        $reportModel= new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $reportModel->truncate();
        $reportModel->detachMySql();
        $reportModel=null;
        //truncamos tabla de Report
        $customModel= new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $customModel->truncate();
        $customModel->detachMySql();
        $customModel=null;

    }

    //ciclo actual
    public function actual(){

        $cicleModel=new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cicle=$cicleModel->getLast();
        $cicleModel->detachMySql();
        $cicleModel=null;
        echo(json_encode($cicle));

    }

}

?>