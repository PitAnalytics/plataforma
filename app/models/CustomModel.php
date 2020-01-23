<?php

class CustomModel extends MySqlConnection implements MySqlOverwriteInterface, MySqlUpdateInterface,MySqlTruncateInterface{

    //inyectamos dependencia de lib desde el constructor
    public function __construct($mySql){

        $this->attachMySql($mySql);

    }

    //obtenemos las cuentas que son editables
    public function table($modules){

        $ids=$this->mySql->selectDistinct("Editable_Mensual","Id"," 1 ","1");
        $editables=[];

        foreach ($ids as $id) {

            //linea para valores de salida
            $line=[];

            //creamosm linea editable y agregamos valores de primer nivel
            $editable = $this->mySql->selectRow("Editable_Mensual",["Id","Cuenta","Super_Concepto","Concepto"],"Id = ".$id," Id ");

            //creamos segundo nivel vacio
            $editable['Montos']=[];

            //iteramos valores por cada modulo para llenar segundo nivel de anidacion
            for ($i=0;$i<count($modules); $i++) {

                //obtenemos montos por modulo y monto
                $ammounts = $this->mySql->selectRow("Editable_Mensual",["Monto","Modulo"],"Id = ".$id." AND Modulo = '".$modules[$i]."'"," Id ");
                $editable['Montos'][]=$ammounts;

            }

            //
            $editables[]=$editable;
            
        }

        //
        return $editables;

    }

    //actualizamos
    public function update($postData){

        foreach ($postData as $data) {

            $dml = "UPDATE Editable_Mensual SET Monto = '".$data['value']."' WHERE "." Id = '".$data['id']."' AND Modulo = '".$data['module']."'; ";
            $this->mySql->query($dml);

        }

    }

    //
    public function overwrite($accounts,$modules){

        //iteramos modulos
        foreach ($modules as $module) {

            //creamos editables vacios
            $custom=[];

            //iteramos cuentas y llenamos 
            foreach ($accounts as $row) {

                //solo agregaremos filas si son editables
                if($row['Editable']===1){

                    //linea de datos para insertar en tabla
                    $line=[];
                    $line['Id']=$row['Id'];
                    $line['Cuenta']=$row['Cuenta'];
                    $line['Super_Concepto']=$row['Super_Concepto'];
                    $line['Concepto']=$row['Concepto'];
                    $line['Monto']=0;
                    $line['Pagado']=$row['Pagado'];
                    $line['Modulo']=$module;
    
                    //llenamos arreglo editable
                    $custom[]=$line;

                }

            }

            

            //insertamos los editables
            $this->mySql->insertBlock("Editable_Mensual",$custom);

        }

    }

    public function truncate(){

        $this->mySql->truncate("Editable_Mensual");

    }

    public function ammount($id,$module){

        $ammount = $this->mySql->selectRow("Editable_Mensual",["Id","Monto"]," Id = ".$id." AND Modulo = '".$module."' ","Id");
        return $ammount['Monto'];

    }

}


?>