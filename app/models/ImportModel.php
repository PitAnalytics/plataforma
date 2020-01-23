<?php

class ImportModel extends BigQueryConnection implements BigQueryImportInterface{

    //inyectamos dependencia de lib desde el constructor
    public function __construct($bigQuery){

        $this->attachBigQuery($bigQuery);

    }

    //importamos a partir de cuentas y parametros
    public function import($accounts,$cecos,$year,$month,$module){

        $subquerys=[];

        //iteramos datos de cuentas
        foreach ($accounts as $account) {

            //parametros pasados
            $account['Anualidad']=$year;
            $account['Mes']=$month;
            $account['Modulo']=$module;

            if($account['Editable']===0){

                //filtros pre-establecidos por contabilidad 
                switch ($account['Filtro']) {
    
                    // caso no pagado
                    case '0':
    
                        $sql="SELECT ROUND(SUM(CAST(DMBTR AS FLOAT64)), 2) AS Monto, '".$account['Anualidad']."' AS Anualidad, '".$account['Modulo']."' AS Modulo, '".$account['Cuenta']."' AS Cuenta, ".strval($account['Id'])." AS Id, '".$account['Mes']."' AS Mes, '".$account['Super_Concepto']."' AS Super_Concepto, '".$account['Concepto']."' AS Concepto, '".strval($account['Pagado'])."' AS Pagado, '".strval($account['Editable'])."' AS Editable ".
                        " FROM (SELECT BUDAT, KOSTL, HKONT, DMBTR FROM `informe-211921.BALANZA.BSEG_".$account['Anualidad']."_".$account['Mes']."` WHERE CAST(SUBSTR(BUDAT,5,2) AS INT64) = ".$account['Mes'].
                        " AND CAST(SUBSTR(BUDAT,1,4) AS INT64) = ".$account['Anualidad'].
                        " AND KOSTL IN (".$cecos.") ".
                        " AND SUBSTR(DBBLG,0,4) <> 'PROV' ".
                        " AND HKONT = '".$account['Cuenta']."') ";     
                        $subquerys[]=$sql;    

                        //echo($sql.'<br><br>');

                        break;
    
                    // caso factor humano
                    case '1':

                        $sql="SELECT ROUND(SUM(CAST(DMBTR AS FLOAT64)), 2) AS Monto, '".$account['Anualidad']."' AS Anualidad, '".$account['Modulo']."' AS Modulo, '".$account['Cuenta']."' AS Cuenta, ".strval($account['Id'])." AS Id, '".$account['Mes']."' AS Mes, '".$account['Super_Concepto']."' AS Super_Concepto, '".$account['Concepto']."' AS Concepto, '".strval($account['Pagado'])."' AS Pagado, '".$account['Editable']."' AS Editable ".
                        " FROM (SELECT BUDAT, KOSTL, HKONT, DMBTR FROM `informe-211921.BALANZA.BSEG_".$account['Anualidad']."_".$account['Mes']."` WHERE CAST(SUBSTR(BUDAT,5,2) AS INT64) = ".$account['Mes'].
                        " AND CAST(SUBSTR(BUDAT,1,4) AS INT64) = ".$account['Anualidad'].
                        " AND KOSTL IN (".$cecos.") ".
                        " AND SUBSTR(DBBLG,0,1) = 'N' ".
                        " AND HKONT = '".$account['Cuenta']."') ";     
                        $subquerys[]=$sql;    

                        //echo($sql.'<br><br>');

                        break;


                    // caso gastos generales
                    case '2':
    
                        $sql="SELECT ROUND(SUM(CAST(DMBTR AS FLOAT64)), 2) AS Monto, '".$account['Anualidad']."' AS Anualidad, '".$account['Modulo']."' AS Modulo, '".$account['Cuenta']."' AS Cuenta, ".strval($account['Id'])." AS Id, '".$account['Mes']."' AS Mes, '".$account['Super_Concepto']."' AS Super_Concepto, '".$account['Concepto']."' AS Concepto, '".strval($account['Pagado'])."' AS Pagado, '".$account['Editable']."' AS Editable ".
                        " FROM (SELECT BUDAT, KOSTL, HKONT, DMBTR FROM `informe-211921.BALANZA.BSEG_".$account['Anualidad']."_".$account['Mes']."` WHERE CAST(SUBSTR(BUDAT,5,2) AS INT64) = ".$account['Mes'].
                        " AND CAST(SUBSTR(BUDAT,1,4) AS INT64) = ".$account['Anualidad'].
                        " AND KOSTL IN (".$cecos.") ".
                        " AND HKONT = '".$account['Cuenta']."') ";     
                        $subquerys[]=$sql;    

                        //echo($sql.'<br><br>');
    
                        break;
                    //


                    // caso gastos generales
                    case '3':
    
                        $sql="SELECT ROUND(SUM(CAST(DMBTR AS FLOAT64)), 2) AS Monto, '".$account['Anualidad']."' AS Anualidad, '".$account['Modulo']."' AS Modulo, '".$account['Cuenta']."' AS Cuenta, ".strval($account['Id'])." AS Id, '".$account['Mes']."' AS Mes, '".$account['Super_Concepto']."' AS Super_Concepto, '".$account['Concepto']."' AS Concepto, '".strval($account['Pagado'])."' AS Pagado, '".$account['Editable']."' AS Editable ".
                        " FROM (SELECT BUDAT, KOSTL, HKONT, DMBTR FROM `informe-211921.BALANZA.BSEG_".$account['Anualidad']."_".$account['Mes']."` WHERE CAST(SUBSTR(BUDAT,5,2) AS INT64) = ".$account['Mes'].
                        " AND CAST(SUBSTR(BUDAT,1,4) AS INT64) = ".$account['Anualidad'].
                        " AND KOSTL IN (".$cecos.") ".
                        " --AND SUBSTR(DBBLG,0,4) <> 'PROV' ".
                        " AND HKONT = '".$account['Cuenta']."') ";     
                        $subquerys[]=$sql;

                        //echo($sql.'<br><br>');

                        break;
                    //
                    default:
    
                        break;
    
                }
    
            }
                 
            // de no ser editable creamos consulta vacia sin busqueda
            else {    }
    
        }

        $uniquery=implode(" UNION ALL ",$subquerys)." ORDER BY (CAST(Id AS INT64)) ";
        //echo($uniquery);
        $subtotalArray = $this->bigQuery->select($uniquery);
        //reporte para curar
        $reportArray=[];
        //iteramos cosnulta y curamos datos
        foreach ($subtotalArray as $row) {

            $line=[];
            //en caso de que un monto sea nulo es llevado a 0
            if($row['Monto']===null){

                $line['Monto'] = 0;

            }
            //en caso de existir un monto es copiado
            else{

                $line['Monto']=$row['Monto'];

            }
            
            $line['Id']=$row['Id'];
            $line['Cuenta']=$row['Cuenta'];
            $line['Super_Concepto']=$row['Super_Concepto'];
            $line['Concepto']=$row['Concepto'];
            $line['Modulo']=$row['Modulo'];
            $line['Pagado']=$row['Pagado'];
            $line['Anualidad']=$row['Anualidad'];
            $line['Mes']=$row['Mes'];

            $reportArray[]=$line;

        }
        //array sin curar es anulado
        $subtotalArray=null;
        //regresamos el arreglo bueno
        return $reportArray;

    }

}