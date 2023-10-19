<?php

namespace app\modules\masterdata\controllers;

use Yii;
use app\models\MMaster;
use app\models\Organization;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Logic;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class OrganizationController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new Organization();

        return $this->render('index', [
            'model' => $model,
        ]);
    }
	
	public function actionLoaddtdatarepository()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];

            $search = '';
            foreach ($column as $cdx => $crow) {
                if ($crow['name'] <> 'nofilter') {
                    if ($crow['search']['value'] != '') {
                        if ($crow['search']['regex'] == 'true') {
                            $search .= ' AND '.$crow['name'].' = :' . $crow['name'];
                            $params[':' . $crow['name']] = $crow['search']['value'];
                        } else {
                            $search .= ' AND LOWER('.$crow['name'].'::VARCHAR) LIKE :' . $crow['name'];
                            $params[':' . $crow['name']] = '%' . strtolower($crow['search']['value']) . '%';
                        }
                    }
                }
            }
			
			$datawhere = Organization::find();
			$datawhere->andWhere(LTRIM($search, ' AND '), $params);
			$datawhere->andWhere(['company_id'=>Yii::$app->user->identity->company_id, 'is_active'=>Logic::statusActive()]);
			
			$datacount = $datawhere->count();
			$dataall = $datawhere->limit($length)->offset($start)->orderBy(['org_id'=>SORT_ASC])->all();	
				
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
						$data['data'][$index][] = '<button onclick="showformdr('.$value->org_id.', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button> <button onclick="showformdr('.$value->org_id.', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
						$data['data'][$index][] = $value['company_code'];
						$data['data'][$index][] = $value['company_name'];
						$data['data'][$index][] = $value['org_code'];
						$data['data'][$index][] = $value['org_abbr'];
						$data['data'][$index][] = $value['org_name'];
						$data['data'][$index][] = $value['psa_code'];
						$data['data'][$index][] = $value['psa_name'];
						$data['data'][$index][] = $value['org_parent'];
						$data['data'][$index][] = $value['is_chief'] ? 'YES' : 'NO';
						$data['data'][$index][] = $value['unit_code'];
						$data['data'][$index][] = $value['unit_name'];
						$data['data'][$index][] = $value['band_code'];
						$data['data'][$index][] = $value['band_name'];
						$data['data'][$index][] = $value['jobposition_code'];
						$data['data'][$index][] = $value['jobposition_name'];
						$data['data'][$index][] = $value['jobfunction_code'];
						$data['data'][$index][] = $value['jobfunction_name'];
						$data['data'][$index][] = $value['created']['person_name'];
						$data['data'][$index][] = Logic::getIndoDate($value['created_time']).' '.date('H:i:s', strtotime($value['created_time']));
						$data['data'][$index][] = !empty($value['updated_by']) ? $value['updated']['person_name'] : '-';
						$data['data'][$index][] = !empty($value['updated_time']) ? Logic::getIndoDate($value['updated_time']).' '.date('H:i:s', strtotime($value['updated_time'])) : '-';

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
	
	public function actionShowformdr() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : strip_tags($_POST['dataid']);
                $dataaction = strip_tags($_POST['dataaction']);
				
                $model = Organization::findOne($dataid);
                if(empty($model)) $model = new Organization();

                return $this->renderPartial('_formdr', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionCruddr() {
		try {
			$connection = Yii::$app->db;
			$transaction = $connection->beginTransaction();
			
			if (Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $dataid = $_POST['dataid'] == '' ? NULL : strip_tags($_POST['dataid']);
                $dataaction = strip_tags($_POST['dataaction']);

                if ($dataaction == 'Hapus') {
					$model = Organization::findOne($dataid);
					if(!empty($model)) {
						$model->is_active = Logic::statusInActive();
						$model->deleted_by = Yii::$app->user->identity->employee->person_id;
						$model->deleted_time = date('Y-m-d H:i:s');
						if($model->save()){
							$transaction->commit();
							return Logic::_sendResponse(200, 'Success', 'Data Repository has been successfully deleted.');
						}
					}else{
						return Logic::_sendResponse(404, 'Failed', 'Data Repository not found.');
					}
                }else if($dataaction == 'Edit' || $dataaction == 'Tambah'){
					$post = Yii::$app->request->post();
					$model = Organization::findOne($dataid);
					if(empty($model)){
						$model = new Organization;
						$model->load($post);
						$model->company_id = Yii::$app->user->identity->company_id;
						$model->company_code = $model->company->code;
						$model->company_name = $model->company->name;
						$model->company_description = $model->company->description;
						$model->psa_code = $model->psa->code;
						$model->psa_name = $model->psa->name;
						$model->psa_description = $model->psa->description;
						
						$model->band_code = $model->band->code;
						$model->band_name = $model->band->name;
						$model->band_description = $model->band->description;
						
						$model->jobposition_code = $model->jobposition->code;
						$model->jobposition_name = $model->jobposition->name;
						$model->jobposition_description = $model->jobposition->description;
						
						$model->jobfunction_code = $model->jobfunction->code;
						$model->jobfunction_name = $model->jobfunction->name;
						$model->jobfunction_description = $model->jobfunction->description;
						
						$model->created_by = Yii::$app->user->identity->employee->person_id;
						$model->created_time = date('Y-m-d H:i:s');
						$model->is_active = Logic::statusActive();
					}else{
						$model->load($post);
						$model->company_id = Yii::$app->user->identity->company_id;
						$model->company_code = $model->company->code;
						$model->company_name = $model->company->name;
						$model->company_description = $model->company->description;
						$model->psa_code = $model->psa->code;
						$model->psa_name = $model->psa->name;
						$model->psa_description = $model->psa->description;
						
						$model->band_code = $model->band->code;
						$model->band_name = $model->band->name;
						$model->band_description = $model->band->description;
						
						$model->jobposition_code = $model->jobposition->code;
						$model->jobposition_name = $model->jobposition->name;
						$model->jobposition_description = $model->jobposition->description;
						
						$model->jobfunction_code = $model->jobfunction->code;
						$model->jobfunction_name = $model->jobfunction->name;
						$model->jobfunction_description = $model->jobfunction->description;
						
						$model->updated_by = Yii::$app->user->identity->employee->person_id;
						$model->updated_time = date('Y-m-d H:i:s');
					}
					// echo '<pre>';
					// print_r($model);exit;
					
					if($model->validate()){
						$model->save();
						$transaction->commit();
						return Logic::_sendResponse(200, 'Success', 'Data Repository has been submitted.');
					}else{	
						$errors = $model->errors;
						$message = '<ol>';
						foreach ($errors as $edx => $erow) {
							$message .= '<li>' . $erow[0] . '</li>';
						}
						$message .= '</ol>';
						
						$transaction->rollback();
						return Logic::_sendResponse(200, 'Failed', $message);
					}
                }else{
					return Logic::_sendResponse(404, 'Failed', 'Action not found.');
                }
			}
		} catch (\Exception $e) {
			$transaction->rollback();
			return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
		}
    }
}
