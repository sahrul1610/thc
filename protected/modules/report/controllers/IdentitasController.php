<?php

namespace app\modules\report\controllers;
use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\MMaster;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Logic;
// require_once '[PATH/TO]/src/Spout/Autoloader/autoload.php';
require_once 'protected/vendor/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use app\models\EmployeeIdentity;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Cocur\Slugify\Slugify;

class IdentitasController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new EmployeeIdentity();

        return $this->render('index', [
            'model' => $model,
        ]);
        //return $this->render('index');
    }

    public function actionLoaddtidentitas() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$person_id = Yii::$app->session->get('person_id');
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];
            $custom_search = $_POST['custom_search'];

            $search = '';
            foreach ($column as $cdx => $crow) {
                if ($crow['name'] <> 'nofilter') {
                    if ($crow['search']['value'] != '') {
                        if ($crow['search']['regex'] == 'true') {
                            $search .= ' AND ' . $crow['name'] . ' = :' . $crow['name'].$cdx;
                            $params[':' . $crow['name'].$cdx] = $crow['search']['value'];
                        } else {
                            $search .= ' AND LOWER(' . $crow['name'] . '::VARCHAR) LIKE :' . $crow['name'].$cdx;
                            $params[':' . $crow['name'].$cdx] = '%' . strtolower($crow['search']['value']) . '%';
                        }
                    }
                }
            }
			
			$datawhere = EmployeeIdentity::find();
			
            $datawhere->andWhere('person_id = :person_id AND app_status != :app_status', [':person_id' => $person_id, ':app_status' => 'DELETED']);
            $datawhere->andWhere(LTRIM($search, ' AND '), $params);
			
            $datacount = $datawhere->count();
            $dataall = $datawhere->limit($length)->offset($start)->all();
			
            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data['draw'] = $_POST['draw'];
                    $data['recordsTotal'] = $datacount;
                    $data['recordsFiltered'] = $datacount;
                    $data['start'] = $start;
                    $data['length'] = $length;
                    $data['data'][$index]['DT_RowId'] = $i;
                    $data['data'][$index][] = $start + $i;
                  
					// if ($value->app_status != 'ON PROGRESS') {
					// 	$data['data'][$index][] = '<button onclick="showformidentitas(' . $value->identity_id . ', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button><button onclick="showformidentitas(' . $value->identity_id . ', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
					// }else{
					// 	$data['data'][$index][] = '-';
					// }
					
                    $data['data'][$index][] = '<button  class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->app_status).'</button>';
                    
                    $data['data'][$index][] = $value->employee_id;
                    $data['data'][$index][] = strip_tags($value->person_name);
                    //$data['data'][$index][] = $value->identitytype->name;
                    $data['data'][$index][] = $value->identitytype_name;
                    $data['data'][$index][] = strip_tags($value->no_identity);
                    $data['data'][$index][] = file_exists(\Yii::getAlias('@webroot').$value->url_scan_identity) ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $value->url_scan_identity]) . '"><i class="icofont icofont-file-document"></i></a>' : '-';
                    $i++;
                }
            } else {
                $data['draw'] = $_POST['draw'];
                $data['recordsTotal'] = 0;
                $data['recordsFiltered'] = 0;
                $data['start'] = $start;
                $data['length'] = $length;
                $data['data'] = [];
            }
            if($custom_search == 'buttonexcel'){
				$model = new EmployeeIdentity;
				$header = $model->customAttributeLabelsMonitoringExcel();
				
				$directory = \Yii::getAlias('@webroot/storage').'/excel';
				$filepath = $directory.'/monitoring_identity.xlsx';
				$fileName = 'coba.xlsx';
				$border = (new BorderBuilder())
					->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
					->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
					->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
					->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
					->build();
				
				$defaultStyle = (new StyleBuilder())
					->setBorder($border)
					->build();
				
				$writer = WriterFactory::create(Type::XLSX);
				$writer->setShouldUseCellAutosizing(true);
				$writer->setDefaultRowStyle($defaultStyle)->openToFile($filepath);
				//$writer->setDefaultRowStyle($defaultStyle)->openToBrowser($fileName);
				$writer->getCurrentSheet()->setName('Identity');
				
				$writer->addRow($header);
				
				$dataexcel = $datawhere->limit(-1)->all();
                
				$no=1;
				if (!empty($dataexcel)) {
					foreach ($dataexcel as $index => $value) {
						$body[$index][]  = $no;
                        $body[$index][] = $value->app_status;
						$body[$index][] = $value->employee_id;
						$body[$index][] = $value->person_name;
						$body[$index][] = $value->identitytype_name;
						$body[$index][] = strip_tags($value->no_identity);
						
						$no++;
					}
					$writer->addRows($body);
				}
				
				$writer->close();
				
				$urilink = Url::base().'/storage/excel';
				
				$data['code'] = 200;
				$data['status'] = 'Success';
				$data['message'] = 'Excel has been successfully downloaded';
				$data['url'] = $urilink.'/monitoring_identity.xlsx?'.time();
			}
            return $data;
        }
    }

    public function actionxlxl(){

    }
}
