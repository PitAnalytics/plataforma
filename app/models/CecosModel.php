<?php

class CecosModel extends MySqlConnection implements MySqlIndexInterface{

    //
    public function __construct($mySql){

        $this->attachMySql($mySql);

    }

    // todos los cecos
    public function index(){

        $cecos = $this->mySql->select("Cecos",["Id","Modulo","Kostl","Incluido"]," 1 ","Id","assoc");
        if(count($cecos)){

            for ($i=0; $i <count($cecos); $i++) { 

                $cecos[$i]['Id']=intval($cecos[$i]['Id']);
                $cecos[$i]['Incluido']=intval($cecos[$i]['Incluido']);
    
            }

        }

        return $cecos;

    }

    //cambiar si un ceco es editable o no
    public function update($postData){

        $dml = "UPDATE Cecos SET Incluido = ".$postData['value']." WHERE Id = ".$postData['id'].";";
        $this->mySql->query($dml);

    }

    //arreglo de cecos en un modulo
    public function cecosString($module){

        $cecos = $this->mySql->selectDistinct("Cecos","Kostl","Modulo = "."'".$module."' AND Incluido ='1' "," Kostl ");
        $cecosString="'".implode("','",$cecos)."'";
        return $cecosString;
     
    }

    //eliminamos centro de costo individualmente
    public function delete($postData){

        $this->mySql->delete("Cecos"," Id = '".$postData['id']."'");

    }

    public function add($postData){

        $this->mySql->insertLine("Cecos",$postData);

    }

    //buscamos de forma dinamica cada centro de costo
    public function search($module,$ceco){

        //en caso de no existir seleccion apareceran todos
        if(($ceco==="all")&&($module==="all")){

            $cecos = $this->mySql->select("Cecos",["Id","Modulo","Kostl","Incluido"]," 1 ","Id","assoc");

            if(count($cecos)){

                for ($i=0; $i <count($cecos); $i++) { 
    
                    $cecos[$i]['Id']=intval($cecos[$i]['Id']);
                    $cecos[$i]['Incluido']=intval($cecos[$i]['Incluido']);
        
                }
    
            }

            return $cecos;

        }

        //en caso de existir seleccion en modulo buscamos modulo en todos los posibles cecos
        else if (($ceco==="all")&&($module!=="all")){

            $cecos = $this->mySql->select("Cecos",["Id","Modulo","Kostl","Incluido"]," Modulo='".$module."' ","Id","assoc");

            if(count($cecos)){

                for ($i=0; $i <count($cecos); $i++) { 
    
                    $cecos[$i]['Id']=intval($cecos[$i]['Id']);
                    $cecos[$i]['Incluido']=intval($cecos[$i]['Incluido']);
        
                }
    
            }

            return $cecos;

        }

        //en caso de existir busqueda en ceco buscamos parecidos
        else if (($ceco!=="all")&&($module==="all")){

            $cecos = $this->mySql->select("Cecos",["Id","Modulo","Kostl","Incluido"]," Kostl LIKE '".$ceco."%' ","Id","assoc");

            if(count($cecos)){

                for ($i=0; $i <count($cecos); $i++) { 
    
                    $cecos[$i]['Id']=intval($cecos[$i]['Id']);
                    $cecos[$i]['Incluido']=intval($cecos[$i]['Incluido']);
        
                }
    
            }

            return $cecos;

        }

        //en caso de existir ambos criterios aplicamos busqueda incluyendolos
        else{

            $cecos = $this->mySql->select("Cecos",["Id","Modulo","Kostl","Incluido"]," Modulo = '".$module."' AND Kostl LIKE '".$ceco."%' ","Id","assoc");

            if(count($cecos)){

                for ($i=0; $i <count($cecos); $i++) { 
    
                    $cecos[$i]['Id']=intval($cecos[$i]['Id']);
                    $cecos[$i]['Incluido']=intval($cecos[$i]['Incluido']);
        
                }
    
            }

            return $cecos;

        }

    }


    
}

?>