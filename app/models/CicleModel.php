<?php

class CicleModel extends MySqlConnection implements MySqlIndexInterface {

    //
    public function __construct($mySql){

        $this->attachMySql($mySql);

    }

    //indice
    public function index(){

        $cicles=$this->mySql->select("Ciclos",["Id","Anualidad","Mes"]," 1 ","Id","assoc");
        return $cicles;

    }

    //
    public function forward(){

        //
        $lastId=$this->mySql->selectCount("Ciclos","Id"," 1 ");
        $cicle=$this->mySql->selectRow("Ciclos",["Id","Anualidad","Mes","Modulos"]," Id = ".$lastId," Id ");

        //
        $newCicle=[];
        $newCicle["Id"]=strval(intval($lastId)+1);
        $newCicle["Anualidad"]="";
        $newCicle["Mes"]="";
        $newCicle["Modulos"]=$cicle['Modulos'];

        //
        $month=intval($cicle['Mes']);
        $year=intval($cicle['Anualidad']);
        $month++;

        //
        if($month>12){

            $month=1;
            $year++;

        }
        else{  }

        //creamos nuevo ciclo y lo plasmamos
        $newCicle['Anualidad']=strval($year);
        $newCicle['Mes']=strval($month);
        $this->mySql->insertLine("Ciclos",$newCicle);

    }

    //
    public function getLast(){

        $lastId=$this->mySql->selectCount("Ciclos","Id"," 1 ");
        $cicle=$this->mySql->selectRow("Ciclos",["Id","Anualidad","Mes","Modulos"]," Id =".$lastId,"Id");

        $cicleStruct=[];

        $cicleStruct['Id']=$cicle['Id'];
        $cicleStruct['Anualidad']=$cicle['Anualidad'];
        $cicleStruct['Mes']=$cicle['Mes'];
        $cicleStruct['Modules']=explode(",",$cicle['Modulos']);
                
        return $cicleStruct;
        
    }

    public function rewind(){

        $lastId=$this->mySql->selectCount("Ciclos","Id"," 1 ");
        $this->mySql->delete("Ciclos"," Id = '".$lastId."'");
        return true;

    }

    
}

?>