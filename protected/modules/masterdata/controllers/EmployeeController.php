<?php

namespace app\modules\masterdata\controllers;

use Yii;
use app\models\MMaster;
use app\models\Employee;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Logic;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class EmployeeController extends Controller
{
    public function actionIndex()
    {
        $model = new Employee();

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
			
			$datawhere = Employee::find();
			$datawhere->andWhere(LTRIM($search, ' AND '), $params);
			$datawhere->andWhere(['company_id'=>Yii::$app->user->identity->company_id, 'is_active'=>1]);
			
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
						$data['data'][$index][] = '<a class="btn btn-xs btn-pill btn-primary" href="'.Url::to(['/profile/default/index', 'person_id'=>$value['person_id']]).'"><i class="icofont icofont-eye"></i></a> <button onclick="showformdr('.$value->person_id.', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button> <button onclick="showformdr('.$value->person_id.', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
						$data['data'][$index][] = $value['company_code'];
						$data['data'][$index][] = $value['company_name'];
						$data['data'][$index][] = $value['employee_id'];
						$data['data'][$index][] = $value['person_name'];
						$data['data'][$index][] = $value['sex'] == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN';
						$data['data'][$index][] = $value['org_code'];
						$data['data'][$index][] = $value['org_abbr'];
						$data['data'][$index][] = $value['org_name'];
						$data['data'][$index][] = $value['org_parent'];
						$data['data'][$index][] = $value['org_is_chief'] ? 'YES' : 'NO';
						$data['data'][$index][] = $value['org_unit_code'];
						$data['data'][$index][] = $value['org_unit_name'];
						$data['data'][$index][] = $value['empgroup_code'];
						$data['data'][$index][] = $value['empgroup_name'];
						$data['data'][$index][] = $value['empsubgroup_code'];
						$data['data'][$index][] = $value['empsubgroup_name'];
						$data['data'][$index][] = $value['band_code'];
						$data['data'][$index][] = $value['band_name'];
						$data['data'][$index][] = $value['psa_code'];
						$data['data'][$index][] = $value['psa_name'];
						$data['data'][$index][] = $value['jobposition_code'];
						$data['data'][$index][] = $value['jobposition_name'];
						$data['data'][$index][] = $value['jobfunction_code'];
						$data['data'][$index][] = $value['jobfunction_name'];
						$data['data'][$index][] = $value['ethnic_code'];
						$data['data'][$index][] = $value['ethnic_name'];
						$data['data'][$index][] = $value['religion_code'];
						$data['data'][$index][] = $value['religion_name'];
						$data['data'][$index][] = $value['payroll_code'];
						$data['data'][$index][] = $value['payroll_name'];
						$data['data'][$index][] = $value['marital_code'];
						$data['data'][$index][] = $value['marital_name'];
						$data['data'][$index][] = $value['town_of_birth_code'];
						$data['data'][$index][] = $value['town_of_birth_name'];
						$data['data'][$index][] = $value['date_of_birth'];
						$data['data'][$index][] = $value['date_of_hire'];
						$data['data'][$index][] = $value['date_of_work'];
						$data['data'][$index][] = $value['date_of_retire'];
						$data['data'][$index][] = $value['date_of_kdmp'];
						$data['data'][$index][] = $value['date_of_position'];
						$data['data'][$index][] = $value['date_of_band_position'];
						$data['data'][$index][] = $value['date_of_adjusted'];
						$data['data'][$index][] = $value['date_of_dedicated'];
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
				
                $model = Employee::findOne($dataid);
                if(empty($model)) $model = new Employee();

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
					$model = Employee::findOne($dataid);
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
					$model = Employee::findOne($dataid);
					if(empty($model)){
						$model = new Employee;
						$model->load($post);
						$model->company_id = Yii::$app->user->identity->company_id;
						$model->company_code = $model->company->code;
						$model->company_name = $model->company->name;
						$model->company_description = $model->company->description;
						
						$model->org_code = $model->org->org_code;
						$model->org_name = $model->org->org_name;
						$model->org_parent = $model->org->org_parent;
						$model->org_abbr = $model->org->org_abbr;
						$model->org_is_chief = $model->org->is_chief;
						$model->org_unit_code = $model->org->unit_code;
						$model->org_unit_name = $model->org->unit_name;
						
						$model->empgroup_code = $model->empgroup->code;
						$model->empgroup_name = $model->empgroup->name;
						$model->empgroup_description = $model->empgroup->description;
						
						$model->empsubgroup_code = $model->empsubgroup->code;
						$model->empsubgroup_name = $model->empsubgroup->name;
						$model->empsubgroup_description = $model->empsubgroup->description;
						
						$model->band_code = $model->org->band_code;
						$model->band_name = $model->org->band_name;
						$model->band_description = $model->org->band_description;
						
						$model->psa_code = $model->org->psa_code;
						$model->psa_name = $model->org->psa_name;
						$model->psa_description = $model->org->psa_description;
						
						$model->jobposition_code = $model->org->jobposition_code;
						$model->jobposition_name = $model->org->jobposition_name;
						$model->jobposition_description = $model->org->jobposition_description;
						
						$model->jobfunction_code = $model->org->jobfunction_code;
						$model->jobfunction_name = $model->org->jobfunction_name;
						$model->jobfunction_description = $model->org->jobfunction_description;
						
						$model->ethnic_code = $model->ethnic->code;
						$model->ethnic_name = $model->ethnic->name;
						$model->ethnic_description = $model->ethnic->description;
						
						$model->religion_code = $model->religion->code;
						$model->religion_name = $model->religion->name;
						$model->religion_description = $model->religion->description;
						
						$model->payroll_code = $model->payroll->code;
						$model->payroll_name = $model->payroll->name;
						$model->payroll_description = $model->payroll->description;
						
						$model->marital_code = $model->marital->code;
						$model->marital_name = $model->marital->name;
						$model->marital_description = $model->marital->description;
						
						$model->town_of_birth_code = $model->area->code;
						$model->town_of_birth_name = $model->area->name;
						$model->town_of_birth_description = $model->area->description;
						
						$model->created_by = Yii::$app->user->identity->employee->person_id;
						$model->created_time = date('Y-m-d H:i:s');
					}else{
						$model->load($post);
						$model->company_id = Yii::$app->user->identity->company_id;
						$model->company_code = $model->company->code;
						$model->company_name = $model->company->name;
						$model->company_description = $model->company->description;
						
						$model->org_code = $model->org->org_code;
						$model->org_name = $model->org->org_name;
						$model->org_parent = $model->org->org_parent;
						$model->org_abbr = $model->org->org_abbr;
						$model->org_is_chief = $model->org->is_chief;
						$model->org_unit_code = $model->org->unit_code;
						$model->org_unit_name = $model->org->unit_name;
						
						$model->empgroup_code = $model->empgroup->code;
						$model->empgroup_name = $model->empgroup->name;
						$model->empgroup_description = $model->empgroup->description;
						
						$model->empsubgroup_code = $model->empsubgroup->code;
						$model->empsubgroup_name = $model->empsubgroup->name;
						$model->empsubgroup_description = $model->empsubgroup->description;
						
						$model->band_code = $model->org->band_code;
						$model->band_name = $model->org->band_name;
						$model->band_description = $model->org->band_description;
						
						$model->psa_code = $model->org->psa_code;
						$model->psa_name = $model->org->psa_name;
						$model->psa_description = $model->org->psa_description;
						
						$model->jobposition_code = $model->org->jobposition_code;
						$model->jobposition_name = $model->org->jobposition_name;
						$model->jobposition_description = $model->org->jobposition_description;
						
						$model->jobfunction_code = $model->org->jobfunction_code;
						$model->jobfunction_name = $model->org->jobfunction_name;
						$model->jobfunction_description = $model->org->jobfunction_description;
						
						$model->ethnic_code = $model->ethnic->code;
						$model->ethnic_name = $model->ethnic->name;
						$model->ethnic_description = $model->ethnic->description;
						
						$model->religion_code = $model->religion->code;
						$model->religion_name = $model->religion->name;
						$model->religion_description = $model->religion->description;
						
						$model->payroll_code = $model->payroll->code;
						$model->payroll_name = $model->payroll->name;
						$model->payroll_description = $model->payroll->description;
						
						$model->marital_code = $model->marital->code;
						$model->marital_name = $model->marital->name;
						$model->marital_description = $model->marital->description;
						
						$model->town_of_birth_code = $model->area->code;
						$model->town_of_birth_name = $model->area->name;
						$model->town_of_birth_description = $model->area->description;
						
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
