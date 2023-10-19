<?php

namespace app\modules\monitoring\controllers;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Employee;
use app\components\Logic;
use kartik\mpdf\Pdf;
use Cocur\Slugify\Slugify;

class CvController extends \yii\web\Controller
{
    public function actionIndex()
    {
		$model = new Employee;
		
        return $this->render('index', [
			'model'=>$model
		]);
    }
	
    public function actionLoaddtcv() {
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
			
			$datawhere = Employee::find();
			
            //$datawhere->andWhere('person_id = :person_id AND app_status != :app_status', [':person_id' => $person_id, ':app_status' => 'DELETED']);
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
                  
					if ($value->person_id) {
                        //$data['data'][$index][] = '<button onclick="showformcv('.$value->person_id.')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button>';
						//$data['data'][$index][] = '<button onclick=" "  class="btn btn-xs btn-pill btn-primary"><i class="icofont icofont-file-pdf"></i></button>';
                       $data['data'][$index][] = '<a target="_blank" href="'.Url::to(['/profile/default/printpdf', 'person_id'=>$value->person_id]).'" class="btn btn-xs btn-pill btn-primary"><i class="icofont icofont-file-pdf"></i></a>';
					}else{
						$data['data'][$index][] = '-';
					}
					
                    //$data['data'][$index][] = '<button  class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->employee_id).'</button>';
                    //$data['data'][$index][] = '<button  class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->employee_id).'</button>';
                    
                    // Create$data['data'][$index][] = '-';
                    $data['data'][$index][] = $value->employee_id;
                    $data['data'][$index][] = $value->person_name;
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
            
            return $data;
        }
    }

}
