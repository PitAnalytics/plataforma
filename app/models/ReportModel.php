<?php

class ReportModel extends MySqlConnection implements MySqlTruncateInterface,MySqlWriteInterface{

    //inyectamos dependencia de lib desde el constructor
    public function __construct($mySql){

        $this->attachMySql($mySql);

    }

    //chacamos si la tabla tiene datos
    public function check($cicle){

        $year=$cicle['Anualidad'];
        $month=$cicle['Mes'];

        //contamos todas las filas
        $count=intval($this->mySql->selectCount("Informe_Mensual","Id"," Mes = '".$month."' AND Anualidad = '".$year."'"));

        //en caso de haber 1 fila o mas retornamos true 
        if($count){

            return true;

        }

        //en caso contrario retornamos false
        else{

            return false;

        }

    }

    //obtenemos las cuentas que son editables
    public function table($modules){

        $ids=$this->mySql->selectDistinct("Informe_Mensual","Id"," 1 ","1");
        $reports=[];

        foreach ($ids as $id) {

            //linea para valores de salida
            $line=[];

            //creamosm linea editable y agregamos valores de primer nivel
            $generated = $this->mySql->selectRow("Informe_Mensual",["Id","Cuenta","Super_Concepto","Concepto"],"Id = ".$id," Id ");

            //creamos segundo nivel vacio
            $generated['Montos']=[];

            //iteramos valores por cada modulo para llenar segundo nivel de anidacion
            for ($i=0;$i<count($modules); $i++) {

                //obtenemos montos por modulo y monto
                $ammounts = $this->mySql->selectRow("Informe_Mensual",["Monto","Modulo"],"Id = ".$id." AND Modulo = '".$modules[$i]."'"," Id ");
                $generated['Montos'][]=$ammounts;

            }

            //
            $reports[]=$generated;
            
        }

        //
        return $reports;

    }


    //funcion para escribir informe mensual
    public function write($data){

        //escribimos infome mensual a travez de bloque de datos
        $this->mySql->insertBlock("Informe_Mensual",$data);

    }

    //
    public function truncate(){

        //
        $this->mySql->truncate("Informe_Mensual");

    }

    

}

?>