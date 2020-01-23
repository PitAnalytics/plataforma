<?php


/**************************************************************************************************/
/**************************************************************************************************/
/**************************************************************************************************/

//librerias
require_once('../app/libs/BigQuery.php');
require_once('../app/libs/PdoCrud.php');
require_once('../app/libs/Bseg.php');

//interfaces
require_once('../app/interfaces/MySqlInterfaces.php');
require_once('../app/interfaces/BigQueryInterfaces.php');

//clases primitivas
require_once('../app/templates/MySqlConnection.php');
require_once('../app/templates/BigQueryConnection.php');

//modelos
require_once('../app/models/AccountModel.php');
require_once('../app/models/CecosModel.php');
require_once('../app/models/CustomModel.php');
require_once('../app/models/ImportModel.php');
require_once('../app/models/ReportModel.php');
require_once('../app/models/CicleModel.php');
require_once('../app/models/TranscryptModel.php');
require_once('../app/models/StoreModel.php');
require_once('../app/models/UserModel.php');
require_once('../app/models/LoaderModel.php');
require_once('../app/models/ImportBreakdownModel.php');
require_once('../app/models/BreakdownModel.php');


?>