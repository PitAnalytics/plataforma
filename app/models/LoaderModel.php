<?php

//bigquery como primera dependencia (obligatoria)
//bseg como segunda dependencia

class LoaderModel{

    protected $bigQuery;
    protected $bseg;

    //constructor agarra dependencias y las inicializa
    public function __construct($bigQuery,$bseg){

        $this->bigQuery = $bigQuery;
        $this->bseg = $bseg;

    }

    //
    public function scan($folder,$extension){

        $dirArray=scandir($folder);

        $files=[];

        foreach ($dirArray as $file) {
    
            //despeciamos nombres con un punto
            if($file==="."){



            }
    
            //despeciamos nombres con 2 puntos
            else if($file===".."){



            }
    
            //de tener un nombre lo introduciremos al arreglo solo si la extension cuadra
            else{
    
                $fileExplode=explode(".",$file);
                $extensionVal=$fileExplode[count($fileExplode)-1];
    
                if($extensionVal==$extension){
    
                    $fileData=[];
                    $fileData['name']=$file;
                    $fileData['size']=floatval(filesize($folder.'/'.$file))/1024000;
                    $files[]=$fileData;

                }
    
            }


        }

        //regresamos los arreglos
        return $files;
        
    }

    //
    public function load($headerFile,$file){

        $schema=$this->bseg->getSchema($headerFile);

        $loadJob=$this->bigQuery->loadLocalFile(

            ['dataset'=>'MULTIVA','table'=>'BSEGAIO'],
            ['source'=>$file,'format'=>'CSV'],
            $schema,
            ['delimiter'=>'|','quote'=>'"','ignoreUnknowValues'=>true,'allowQuotedNewLines'=>true,'allowJaggedRows'=>false,'nullMarker'=>'\N'],
            ['write'=>'WRITE_APPEND','create'=>'CREATE_NEVER']

        );

        if($loadJob){return true;}

        else{return false;}

    }

}

?>


