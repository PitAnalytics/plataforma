<?php

class desglose extends Controller{

    
    public function buscar($id,$year,$month,$day,$module,$ceco){


        $breakDownModel = new BreakdownModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $breakdown = $breakDownModel->search($id,$year,$month,$day,$module,$ceco);
        $breakDownModel->detachMySql();
        $breakDownModel=null;
        echo(json_encode($breakdown));

    }

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
        $accountModel=null;

        $report=[];

        //iteramos por cada unidad de negocio
        for ($i=0; $i < count($modules); $i++) { 

            //obtenemos cecos por modulo
            $cecosModel = new cecosModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cecos=[];
            $cecos[$i] = $cecosModel->cecosString($modules[$i]);
            $cecosModel->detachMySql();
            $cecosModel=null;

            //actualizamos reporte en base a consultas de bigquery
            $importModel = new ImportBreakdownModel(new BigQuery('informe-211921'));
            $report[$i]=$importModel->import($accounts,$cecos[$i],$year,$month,$modules[$i]);
            $importModel->detachBigQuery();
            $importModel=null;

        }

        $breakdownModel = new BreakdownModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $breakdownModel->delete($cicle);

        for ($i=0; $i <count($report) ; $i++) { 

            $breakdownModel->write($report[$i]);

        }

        $breakdownModel->detachMySql();
        $breakdownModel=null;
        echo('success');

    }

    /*
    public function parche($year,$month){

        //modelo de ciclo de donde obtenemos datosm principales
        $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $cicle=$cicleModel->getLast();
        $modules=$cicle['Modules'];
        $cicleModel->detachMySql();

        //obtenemos cuentas del model de cuentas
        $accountModel=new AccountModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $accounts=$accountModel->index();
        $accountModel->detachMySql();
        $accountModel=null;

        $report=[];

        //iteramos por cada unidad de negocio
        for ($i=0; $i < count($modules); $i++) { 

            //obtenemos cecos por modulo
            $cecosModel = new cecosModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cecos=[];
            $cecos[$i] = $cecosModel->cecosString($modules[$i]);
            $cecosModel->detachMySql();
            $cecosModel=null;

            //actualizamos reporte en base a consultas de bigquery
            $importModel = new ImportBreakdownModel(new BigQuery('informe-211921'));
            $report[$i]=$importModel->import($accounts,$cecos[$i],$year,$month,$modules[$i]);
            $importModel->detachBigQuery();
            $importModel=null;

        }

        $breakdownModel = new BreakdownModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));

        for ($i=0; $i <count($report) ; $i++) { 

            $breakdownModel->write($report[$i]);

        }

        $breakdownModel->detachMySql();
        $breakdownModel=null;
        echo('success');

    }*/

}


?>