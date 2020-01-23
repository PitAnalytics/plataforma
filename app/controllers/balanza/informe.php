<?php

class informe extends Controller{

    //
    public function indice(){

        $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cicle=$cicleModel->getLast();
        $modules=$cicle['Modules'];
        $cicleModel->detachMySql();
        $cicleModel=null;

        $reportModel=new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        
        if($reportModel->check($cicle)){

            $report=$reportModel->table($modules);
            $reportModel->detachMySql();
            $reportModel=null;

            echo(json_encode($report));

        }

        else{

            echo(json_encode([]));

        }

    }

    //
    public function importar(){

        //modelo de ciclo de donde obtenemos datosm principales
        $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cicle=$cicleModel->getLast();
        $year=$cicle['Anualidad'];
        $month=$cicle['Mes'];
        $modules=$cicle['Modules'];
        $cicleModel->detachMySql();

        //obtenemos cuentas del model de cuentas
        $accountModel=new AccountModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $accounts=$accountModel->index();
        $accountModel->detachMySql();

        //obtenemos cecos por modulo
        $cecosModel = new cecosModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cecos=[];
        $cecos[0] = $cecosModel->cecosString('BANCO');
        $cecos[1] = $cecosModel->cecosString('OPERADORA');
        $cecos[2] = $cecosModel->cecosString('SERVICIOS');
        $cecos[3] = $cecosModel->cecosString('GRUPO');
        $cecos[4] = $cecosModel->cecosString('CASA');
        $cecosModel->detachMySql();
        $cecosModel=null;

        //actualizamos reporte en base a consultas de bigquery
        $importModel = new ImportModel(new BigQuery('informe-211921'));
        $report[0]=$importModel->import($accounts,$cecos[0],$year,$month,'BANCO');
        $report[1]=$importModel->import($accounts,$cecos[1],$year,$month,'OPERADORA');
        $report[2]=$importModel->import($accounts,$cecos[2],$year,$month,'SERVICIOS');
        $report[3]=$importModel->import($accounts,$cecos[3],$year,$month,'GRUPO');
        $report[4]=$importModel->import($accounts,$cecos[4],$year,$month,'CASA');
        $importModel->detachBigQuery();
        $importModel=null;

        //los datos tomados de big query son agregados al reporte
        $reportModel = new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $reportModel->truncate();
        $reportModel->write($report[0]);
        $reportModel->write($report[1]);
        $reportModel->write($report[2]);
        $reportModel->write($report[3]);
        $reportModel->write($report[4]);
        $reportModel->detachMySql();
        $reportModel=null;

        //tomamos los datos y los pasamos a store
        $transcryptModel = new TranscryptModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $report = $transcryptModel->transcrypt($accounts,$cicle);
        $transcryptModel->detachMySql();
        $transcryptModel = null;

        //
        $storeModel = new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $storeModel->delete($cicle);
        $storeModel->write($report);
        $storeModel->detachMySql();
        $storeModel = null;

        echo('success');

    }

    

}

?>