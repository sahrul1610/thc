<?php

namespace app\modules\report\controllers;
//require_once 'protected/vendor/box/spout/src/Spout/Autoloader/autoload.php';
require_once 'protected/vendor/spout/src/Spout/Autoloader/autoload.php';
//require_once APPPATH. '/protected/vendor/spout/src/Spout/Autoloader/autoload.php';
use app\models\EmployeeIdentity;

//require_once '[PATH/TO]/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //return $this->render('index');
        echo 'test';
        //$writer = WriterEntityFactory::createXLSXWriter();
        $writer = WriterFactory::create(type::XLSX);
        $model = new EmployeeIdentity();
        //$writer->openToFile($filePath); // write data to a file or to a PHP stream
        $fileName = 'coba.xlsx';
        $writer->openToBrowser($fileName); // stream data directly to the browser
        $singleRow = ['header1', 'header2'];
        $writer->addRow($singleRow);
        $multipleRows = [
                        ['data1', 'data1'],
                        ['test1', 'test2'],
        ];
                    
        $writer->addRows($multipleRows);
        $writer->close();

    
    }

    
    public function actionxlsx(){
        echo 'test';
    }

}
