<?php

class StoreModel extends MySqlConnection implements MySqlWriteInterface{

    //inyectamos dependencia de lib desde el constructor
    public function __construct($mySql){

        $this->attachMySql($mySql);

    }

    //insertar bloque de resumen
    public function write($data){

        $this->mySql->insertBlock("Reporte",$data);

    }

    //obtenemos tabla con modulos presentes
    public function tableMonth($modules,$year,$month){

        //obtener ids presentes en tabla
        $ids=$this->mySql->selectDistinct("Reporte","Id"," 1 ","Id");

        //creamos resumen en blanco
        $summary=[];

        //iteramos por cada id
        foreach ($ids as $id) {

            $line=$this->mySql->selectRow("Reporte",["Id","Cuenta","Super_Concepto","Concepto","Editable","Pagado","Mes","Anualidad"],"Id = '".$id."' AND Anualidad = '".$year."' AND Mes = '".$month."' ","Id");
            $line["Id"]=intval($line["Id"]);
        
            $line["Montos"]=[];
            
            //creamos total llevado a0
            $total=0;
            //iteramos por cada modulo agregando los montos
            for ($i=0; $i<count($modules);$i++) {

                //modulo
                $module=$modules[$i];

                //monto
                $ammount=$this->mySql->selectRow("Reporte",["Monto","Modulo"],"Id = '".$id."' AND Anualidad = '".$year."' AND Mes = '".$month."' AND Modulo = '".$module."' ","Id");
                $ammount["Monto"]=floatval($ammount["Monto"]);
                $line["Montos"][]=$ammount;

                //total
                $total+=floatval($ammount["Monto"]);

                //llegando al final agregamos el total en el segundo nivel
                if($i==4){

                    $totalModule=["Modulo"=>"TOTAL","Monto"=>$total];
                    $line["Montos"][]=$totalModule;

                }

            }

            //agregamos linea al resumen
            $summary[]=$line;

        }

        for ($i=0; $i < count($summary); $i++) { 

            $summary[$i]['Pagado']=intval($summary[$i]['Pagado']);

        }

        //retornamos resumen
        return $summary;

    }

        //obtenemos tabla con modulos presentes
    public function tableMonthAccumulated($modules,$year,$month){

        //obtener ids presentes en tabla
        $ids=$this->mySql->selectDistinct("Reporte","Id"," 1 ","Id");

        //creamos resumen en blanco
        $summary=[];

        //iteramos por cada id
        foreach ($ids as $id) {

            $line=$this->mySql->selectRow("Reporte",["Id","Cuenta","Super_Concepto","Concepto","Pagado","Mes"],"Id = '".$id."' AND Anualidad = '".$year."' AND Mes = '".$month."' ","Id");
        
            $line["Montos"]=[];
            
            //creamos total llevado a0
            $total=0;
            //iteramos por cada modulo agregando los montos
            for ($i=0; $i<count($modules);$i++) {

                //modulo
                $module=$modules[$i];

                //monto
                $ammount["Monto"]=$this->mySql->selectSum("Reporte","Monto","Id = '".$id."' AND Anualidad = '".$year."' AND Mes <= '".$month."' AND Modulo = '".$module."' ","Id");
                $ammount["Modulo"]=$module;
                $line["Montos"][]=$ammount;
            
                //total
                $total+=floatval($ammount["Monto"]);

                //llegando al final agregamos el total en el segundo nivel
                if($i==4){

                    $totalModule=["Modulo"=>"Total","Monto"=>strval($total)];
                    $line["Montos"][]=$totalModule;

                }

            }

            //agregamos linea al resumen
            $summary[]=$line;

        }

        //
        for ($i=0; $i < count($summary); $i++) { 

            $summary[$i]['Pagado']=intval($summary[$i]['Pagado']);

        }


        //retornamos resumen
        return $summary;

    }

    //borramos el mes en curso
    public function delete($cicle){

        //datos del ciclo
        $year=$cicle['Anualidad'];
        $month=$cicle['Mes'];

        //borramos todo en el ciclo
        $this->mySql->query("DELETE FROM Reporte WHERE Anualidad = '".$year."' AND Mes = '".$month."' ;");

    }

    //obtenemos tabla con modulos presentes
    public function tableModule($year,$module){

        //obtener ids presentes en tabla
        $ids=$this->mySql->selectDistinct("Reporte","Id"," 1 ","Id");

        //creamos resumen en blanco
        $summary=[];

        //mes en curso y por tanto ultimo mes de interes
        $months=$this->mySql->selectDistinct("Reporte","Mes"," 1 ","Mes");

        //iteramos por cada id
        foreach ($ids as $id) {

            $line=$this->mySql->selectRow("Reporte",["Id","Cuenta","Super_Concepto","Concepto","Editable","Pagado","Anualidad"],"Id = '".$id."' AND Anualidad = '".$year."' AND Modulo = '".$module."' ","Id");
            $line["Id"]=intval($line["Id"]);
        
            $line["Montos"]=[];
            

            //iteramos por cada modulo agregando los montos
            for ($i=0; $i<count($months);$i++) {

                $month=$months[$i];

                //monto
                $ammount=$this->mySql->selectRow("Reporte",["Monto","Mes"],"Id = '".$id."' AND Anualidad = '".$year."' AND Mes = '".$month."' AND Modulo = '".$module."' ","Id","assoc");
                $ammount["Monto"]=floatval($ammount["Monto"]);
                $line["Montos"][]=$ammount;


            }

            //agregamos linea al resumen
            $summary[]=$line;

        }

        //
        for ($i=0; $i < count($summary); $i++) { 

            $summary[$i]['Pagado']=intval($summary[$i]['Pagado']);

        }


        //retornamos resumen
        return $summary;

    }


    //obtenemos tabla con modulos presentes
    public function tableModuleAccumulated($year,$module){

        //obtener ids presentes en tabla
        $ids=$this->mySql->selectDistinct("Reporte","Id"," 1 ","Id");

        //creamos resumen en blanco
        $summary=[];

        //creamos arreglo vacio de meses
        $months=$this->mySql->selectDistinct("Reporte","Mes"," 1 ","Mes");


        //iteramos por cada id
        foreach ($ids as $id) {

            $line=$this->mySql->selectRow("Reporte",["Id","Cuenta","Super_Concepto","Concepto","Editable","Pagado","Anualidad"],"Id = '".$id."' AND Anualidad = '".$year."' AND Modulo = '".$module."' ","Id");
            $line["Id"]=intval($line["Id"]);
            $line["Montos"]=[];
            

            //iteramos por cada modulo agregando los montos
            for ($i=0; $i<count($months);$i++) {

                $month=$months[$i];

                $ammount["Monto"]=$this->mySql->selectSum("Reporte","Monto","Id = '".$id."' AND Anualidad = '".$year."' AND Mes <= '".$month."' AND Modulo = '".$module."' ","Id");
                $ammount["Mes"]=$month;
                $line["Montos"][]=$ammount;


            }

            //agregamos linea al resumen
            $summary[]=$line;

        }

        //
        for ($i=0; $i < count($summary); $i++) { 

            $summary[$i]['Pagado']=intval($summary[$i]['Pagado']);

        }


        //retornamos resumen
        return $summary;

    }
    
}


?>