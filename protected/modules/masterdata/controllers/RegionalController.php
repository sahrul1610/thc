<?php

namespace app\modules\masterdata\controllers;

use Yii;
use app\models\MMaster;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Logic;
use yii\helpers\Url;

class RegionalController extends Controller
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
	
	private function _getKey(){
		return $this->id == 'regional' ? 'regional' : '';
	}

    public function actionIndex()
    {
        $model = new MMaster();

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
			
			$datawhere = MMaster::find();
			$datawhere->andWhere(LTRIM($search, ' AND '), $params);
			$datawhere->andWhere(['key'=>$this->_getKey(), 'is_active'=>Logic::statusActive()]);
			$datawhere->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]]);
			
			$datacount = $datawhere->count();
			$dataall = $datawhere->limit($length)->offset($start)->orderBy(['order'=>SORT_ASC])->all();	
				
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
						$data['data'][$index][] = '<button onclick="showformdr('.$value->master_id.', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button> <button onclick="showformdr('.$value->master_id.', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
						$data['data'][$index][] = $value['code'];
						$data['data'][$index][] = $value['name'];
						$data['data'][$index][] = $value['description'];
						$data['data'][$index][] = $value['is_others'] ? 'Yes' : 'No';
						$data['data'][$index][] = $value['is_link']  ? 'Yes' : 'No';
						$data['data'][$index][] = $value['order'];
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
				
                $model = MMaster::findOne($dataid);
                if(empty($model)) $model = new MMaster();

                return $this->renderPartial('_formdr', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model
                ]);
            } catch (\Exception $e) {
                return Logic::Exception($e->getMessage());
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
					$model = MMaster::findOne($dataid);
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
					$model = MMaster::findOne($dataid);
					if(empty($model)){
						$model = new MMaster;
						$model->load($post);
						$model->parent_id = Yii::$app->user->identity->company_id;
						$model->key = $this->_getKey();
						$model->order = $model->getLastorder($this->_getKey());
						$model->created_by = Yii::$app->user->identity->employee->person_id;
						$model->created_time = date('Y-m-d H:i:s');
						$model->is_active = Logic::statusActive();
					}else{
						$model->load($post);
						$model->updated_by = Yii::$app->user->identity->employee->person_id;
						$model->updated_time = date('Y-m-d H:i:s');
					}
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
			return Logic::_sendResponse(500, 'Failed', Logic::Exception($e->getMessage()), null, 'application/html');
		}
    }
}
