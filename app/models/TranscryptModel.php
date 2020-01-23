<?php

class TranscryptModel extends MySqlConnection{


    //inyectamos dependencia de lib desde el constructor
    public function __construct($mySql){

        $this->attachMySql($mySql);

    }

    //transcribir
    public function transcrypt($accounts,$cicle){

        $year = $cicle['Anualidad'];
        $month = $cicle['Mes'];
        $modules = $cicle['Modules'];

        $summary=[];

        foreach($modules as $module){

            foreach ($accounts as $account) {

                //iteramos y transcribimos directamente de cuantas
                $line['Id']=strval($account['Id']);
                $line['Cuenta']=$account['Cuenta'];
                $line['Super_Concepto']=$account['Super_Concepto'];
                $line['Concepto']=$account['Concepto'];
                $line['Pagado']=strval($account['Pagado']);
                $line['Modulo']=$module;
                $line['Editable']=strval($account['Editable']);
                $line['Anualidad']=$year;
                $line['Mes']=$month;
                $line['Modulo']=$module;
                
                //iteramos a partir de los valores editables o generados por informe de BQ
                if($line['Editable']==='1'){

                    $line['Monto']=$this->mySql->selectRow("Editable_Mensual",["Monto"]," Id = '".$account['Id']."' AND Modulo = '".$module."' ","Id")["Monto"];

                    //agregamos nueva linea al resumen
                    $summary[]=$line;
    
                }

                else{

                    $line['Monto']=$this->mySql->selectRow("Informe_Mensual",["Monto"]," Id = '".$account['Id']."' AND Modulo = '".$module."' ","Id")["Monto"];

                    //agregamos nueva linea al resumen
                    $summary[]=$line;

                }


            }
        
        }

        return $summary;

    }



}

?>