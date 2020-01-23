<?php

    interface BigQueryConnectionInterface{

        public function attachBigQuery($bigQuery);
        public function detachBigQuery();
    
    }
    
    
    interface BigQueryImportInterface{
    
        public function import($accounts,$cecos,$year,$month,$module);
    
    }
    

?>