<?php

class cuentas extends Controller{

    //mostrar todas las cuentas
    public function indice(){

        //tomamos todas las cuentas y las mostramos
        $accountModel = new AccountModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
        $accounts = $accountModel->index();
        $accountModel->detachMySql();
        echo(json_encode($accounts));

    }

    //actualizar cuentas y resetear informe, resumen y sobre-escribir editables
    public function actualizar(){

        if(isset($_POST['req'])){

            //actualizamos si una cuenta es editable o no 
            $request=$_POST['req'];
            $accountModel = new AccountModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $postData=json_decode($request,true);
            $accountModel->update($postData);

            //tomamos cuentas y desacoplamos
            $accounts=$accountModel->index();
            $accountModel->detachMySql();
            $accountModel=null;

            //tomamos el ciclo
            $cicleModel=new CicleModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $cicle = $cicleModel->getLast();
            $cicleModel->detachMySql();
            $cicleModel=null;

            //sobreescribimos los editables
            $customModel=new CustomModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $customModel->truncate();
            $customModel->overwrite($accounts,$cicle['Modules']);
            $customModel->detachMySql();
            $customModel=null;

            //borramos reporte
            $reportModel=new ReportModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $reportModel->truncate();
            $reportModel->detachMySql();
            $reportModel=null;

            //reporte
            $storeModel = new StoreModel(new PdoCrud(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE));
            $storeModel->delete($cicle);
            $storeModel->detachMySql();
            $storeMOdel = null;

            //si todo sale bien mandamos un success
            echo("success");
    
        }

        //en caso de no existir usuario es denegado
        else{

            echo("denied");

        }



    }


}

?>