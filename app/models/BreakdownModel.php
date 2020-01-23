<?php

class BreakdownModel extends MySqlConnection implements MySqlTruncateInterface,MySqlWriteInterface{

    //
    public function __construct($mySql){

        $this->attachMySql($mySql);

    }

    //
    public function write($data){

        $this->mySql->insertBlock("Desglose",$data);

    }

    //
    public function search($id,$year,$month,$day,$module,$ceco){


        if(($day!=="all")&&($ceco!=="all")){

            $breakdown = $this->mySql->select("Desglose",["Id","Monto","Descripcion","Anualidad","Mes","Dia","Modulo","Ceco"],"Id = '".$id."' AND Anualidad = '".$year."' AND Mes = '".$month."' AND Modulo = '".$module."' AND Dia = '".$day."' AND Ceco LIKE '%".$ceco."%' "," Id ","assoc");

        }

        else if(($day!=="all")&&($ceco==="all")){

            $breakdown = $this->mySql->select("Desglose",["Id","Monto","Descripcion","Anualidad","Mes","Dia","Modulo","Ceco"],"Id = '".$id."' AND Anualidad = '".$year."' AND Mes = '".$month."' AND Modulo = '".$module."' AND Dia = '".$day."'"," Id ","assoc");

        }

        else if(($day==="all")&&($ceco!=="all")){

            $breakdown = $this->mySql->select("Desglose",["Id","Monto","Descripcion","Anualidad","Mes","Dia","Modulo","Ceco"],"Id = '".$id."' AND Anualidad = '".$year."' AND Mes = '".$month."' AND Modulo = '".$module."' AND Ceco LIKE '%".$ceco."%'"," Id ","assoc");

        }

        else{

            $breakdown = $this->mySql->select("Desglose",["Id","Monto","Descripcion","Anualidad","Mes","Dia","Modulo","Ceco"],"Id = '".$id."' AND Anualidad = '".$year."' AND Mes = '".$month."' AND Modulo = '".$module."'"," Id ","assoc");

        }


        return $breakdown;

    }

    public function truncate(){

        $this->mySql->truncate("Desglose");

    }

    //borramos el mes en curso
    public function delete($cicle){

        //datos del ciclo
        $year=$cicle['Anualidad'];
        $month=$cicle['Mes'];

        //borramos todo en el ciclo
        $this->mySql->query("DELETE FROM Desglose WHERE Anualidad = '".$year."' AND Mes = '".$month."' ;");

    }


}

?>