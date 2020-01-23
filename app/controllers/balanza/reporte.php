<?php

class reporte extends Controller{

    public function mensual($year,$month){

        if(isset($year)&&isset($month)){

            //modelo de ciclo de donde obtenemos datosm principales
            $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle=$cicleModel->getLast();
            $modules=$cicle['Modules'];
            $cicleModel->detachMySql();

            //modelo de guardado habilitado
            $storeModel=new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $report = $storeModel->tableMonth($modules,$year,$month);
            $storeModel->detachMySql();
            $storeModel=null;

            //
            echo(json_encode($report));

        }
        else{

            echo('error 403');

        }

    }

    public function modular($year,$module){

        if(isset($year)&&isset($module)){

            //modelo de ciclo de donde obtenemos datosm principales
            $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle=$cicleModel->getLast();
            $modules=$cicle['Modules'];
            $cicleModel->detachMySql();

            //modelo de guardado habilitado
            $storeModel=new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $report = $storeModel->tableModule($year,$module);
            $storeModel->detachMySql();
            $storeModel=null;

            //
            echo(json_encode($report));

        }

        else{

            echo('error 403');

        }

    }

    public function modular_acumulado($year,$module){

        if(isset($year)&&isset($module)){

            //modelo de ciclo de donde obtenemos datosm principales
            $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle=$cicleModel->getLast();
            $cicleModel->detachMySql();

            //modelo de guardado habilitado
            $storeModel=new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $report = $storeModel->tableModuleAccumulated($year,$module);
            $storeModel->detachMySql();
            $storeModel=null;

            //
            echo(json_encode($report));

        }

        else{

            echo('error 403');

        }

    }

    public function acumulado($year,$month){

        if(isset($year)&&isset($month)){

            //modelo de ciclo
            $cicleModel = new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle=$cicleModel->getLast();
            $modules=$cicle['Modules'];
            $cicleModel->detachMySql();
    
            //modelo de guardado habilitado
            $storeModel=new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $report = $storeModel->tableMonthAccumulated($modules,$year,$month);
            $storeModel->detachMySql();
            $storeModel=null;
    
            //
            echo(json_encode($report));

        }

        else{

            echo('error 403');

        }

    }

}

?>