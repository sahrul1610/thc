<?php
namespace app\modules\monitoring\controllers;
require_once(dirname(__FILE__).'/../../../../protected/vendor/box/spout/src/Spout/Autoloader/autoload.php');

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\EmployeeEducation;
use app\components\Logic;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Cocur\Slugify\Slugify;

class EducationController extends \yii\web\Controller
{
    public function actionIndex()
    {
		$model = new EmployeeEducation;
		
        return $this->render('index', [
			'model'=>$model
		]);
    }
	
	public function actionLoaddtpendidikan() {
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
							if($crow['name'] == 'person_id'){
								$search .= ' AND (LOWER(employee_id) LIKE :' . $crow['name'].$cdx.' OR LOWER(person_name) LIKE :' . $crow['name'].$cdx.') ';
								$params[':' . $crow['name'].$cdx] = '%' . strtolower($crow['search']['value']) . '%';
							}else{
								$search .= ' AND LOWER(' . $crow['name'] . '::VARCHAR) LIKE :' . $crow['name'].$cdx;
								$params[':' . $crow['name'].$cdx] = '%' . strtolower($crow['search']['value']) . '%';
							}
                        }
                    }
                }
            }
			
			$datawhere = EmployeeEducation::find();
			$datawhere->andWhere('app_status = :app_status', [':app_status' => 'APPROVED']);
            $datawhere->andWhere(LTRIM($search, ' AND '), $params);
			$datawhere->orderBy(['person_name'=>SORT_ASC]);
			
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
                    $data['data'][$index][] = $value->employee_id.' / '.$value->person_name;
                    $data['data'][$index][] = strip_tags($value->level_code);
                    $data['data'][$index][] = strip_tags($value->institute);
                    $data['data'][$index][] = strip_tags($value->major);
                    $data['data'][$index][] = strip_tags($value->year_of_study);
                    $data['data'][$index][] = strip_tags($value->year_of_passed);
                    $data['data'][$index][] = strip_tags($value->no_identity);
                    $data['data'][$index][] = file_exists(\Yii::getAlias('@webroot').$value->url_scan_identity) && $value->url_scan_identity ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $value->url_scan_identity]) . '"><i class="icofont icofont-file-document"></i></a>' : '';
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
				$model = new EmployeeEducation;
				$header = $model->customAttributeLabelsMonitoringExcel();
				
				$directory = \Yii::getAlias('@webroot/storage').'/excel';
				$filepath = $directory.'/monitoring_education.xlsx';
				
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
				$writer->getCurrentSheet()->setName('Education');
				
				$writer->addRow($header);
				
				$dataexcel = $datawhere->limit(-1)->all();
				$no=1;
				if (!empty($dataexcel)) {
					foreach ($dataexcel as $index => $value) {
						$body[$index][]  = $no;
						$body[$index][] = $value->employee_id.' / '.$value->person_name;
						$body[$index][] = strip_tags($value->level_code);
						$body[$index][] = strip_tags($value->institute);
						$body[$index][] = strip_tags($value->major);
						$body[$index][] = strip_tags($value->year_of_study);
						$body[$index][] = strip_tags($value->year_of_passed);
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
				$data['url'] = $urilink.'/monitoring_education.xlsx?'.time();
			}
			
			if($custom_search == 'buttonzip'){
				$slugify = new Slugify();
				$zip = new \ZipArchive();
				
				$directory = \Yii::getAlias('@webroot/storage').'/zip';
				$filepath = $directory.'/document_education.zip';
				if($zip->open($filepath,\ZIPARCHIVE::CREATE) !== true) {
					return false;
				}
				
				$datazipcount = 0;
				$datazip = $datawhere->limit(-1)->all();
				if(!empty($datazip)){
					foreach($datazip as $ddx=>$drow){
						$file_path =  \Yii::getAlias('@webroot').$drow->url_scan_identity;
						$exp = explode('.', $drow->url_scan_identity);
						if(file_exists($file_path) && $drow->url_scan_identity){
							$setslug = $slugify->slugify($drow->person_name.' '.$drow->level_code);
							$file_name = $setslug.'.'.end($exp);
							$zip->addFile($file_path,$file_name);
							
							$datazipcount += 1;
						}
					}
				}
				
				if($datazipcount > 0){
					$zip->close();  
				
					$urilink = Url::base().'/storage/zip';
					$data['code'] = 200;
					$data['status'] = 'Success';
					$data['message'] = 'Zip has been successfully downloaded';
					$data['url'] = $urilink.'/document_education.zip?'.time();					
				}else{
					$data['code'] = 200;
					$data['status'] = 'Failed';
					$data['message'] = 'Zip failed downloaded cause file doesnt exist';
				}
			}

            return $data;
        }
    }
}
