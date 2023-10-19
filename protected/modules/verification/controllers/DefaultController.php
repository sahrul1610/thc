<?php

namespace app\modules\verification\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Logic;
use app\models\MMaster;
use app\models\Approval;
use app\models\VListApproval;
use app\models\EmployeeGeneral;
use app\models\Employee;

/**
 * Default controller for the `verification` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
	
	public function actionShowannouncement() {
        if (Yii::$app->request->isAjax) {
            try {
				$datawhere = MMaster::find();
				$datawhere->andWhere(LTRIM($search, ' AND '), $params);
				$datawhere->andWhere(['key'=>'verification_profile', 'is_active'=>Logic::statusActive()]);
				$datawhere->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]]);
				
				$model = $datawhere->one();
				
                return $this->renderPartial('_pemberitahuan', [
					'model'=>$model
				]);
            } catch (\Exception $e) {
               return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoaddataprofile(){
		if(Yii::$app->request->isAjax) {
			try {
				$datanav = strip_tags($_POST['datanav']);
				
				$model = new VListApproval;
				if($datanav == '01'){
					$header = $model->customAttributeLabelsNeed();
				}else{
					$header = $model->customAttributeLabelsExceptNeed();
				}
				
				$listapptype = MMaster::find()->andWhere(['key'=>'approval_type', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				$approvaltype = Html::dropDownList('apptype_id', null,ArrayHelper::map($listapptype, 'master_id', 'name'), ['class'=>'form-control form-control-primary', 'prompt'=>'CHOOSE '.$model->getAttributeLabel('apptype_id').'']);
				
				return	$this->renderPartial('_loaddataprofile', ['datanav'=>$datanav, 'model'=>$model, 'approvaltype'=>$approvaltype, 'header'=>$header]);
			}catch(\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
			}
		}
	}
	
	public function actionLoaddtprofile(){
		if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];
			$datanav = strip_tags($_POST['datanav']);
			
            $search = '';
            foreach ($column as $cdx => $crow) {
                if ($crow['name'] <> 'nofilter') {
                    if ($crow['search']['value'] != '') {
                        if ($crow['search']['regex'] == 'true') {
                            $search .= ' AND ' . $crow['name'] . ' = :' . $crow['name'].$cdx;
                            $params[':' . $crow['name'].$cdx] = $crow['search']['value'];
                        } else {
							if($crow['name'] == 'person_id_sender'){
								$search .= ' AND LOWER(employee_id_sender::VARCHAR) LIKE :'.$crow['name'].$cdx;
							}else{
								$search .= ' AND LOWER(' . $crow['name'] . '::VARCHAR) LIKE :' . $crow['name'].$cdx;
							}
							$params[':' . $crow['name'].$cdx] = '%' . strtolower($crow['search']['value']) . '%';
                        }
                    }
                }
            }
			
			$datawhere = VListApproval::find();
			if($datanav == 01){
				$datawhere->andWhere(['in', 'app_status', ['SUBMIT', 'REQUEST DELETE']]);
			}else if($datanav == 02){
				$datawhere->andWhere(['app_status'=>'APPROVED']);
			}else{
				$datawhere->andWhere(['app_status'=>'RETURN']);
			}	
			
			$datawhere->andWhere('company_id = :company_id', [':company_id' => Yii::$app->user->identity->employee->company_id]);
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
                    $data['data'][$index]['approval_id'] = $value->approval_id;
                    $data['data'][$index][] = null;
                    $data['data'][$index][] = $start + $i;
					$data['data'][$index][]  = '<button onclick="showformprocess('.$value['approval_id'].', \''.$datanav.'\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary"><i class="icofont icofont-eye"></i></button>';
                    $data['data'][$index][] = $value->apptype_name;
					if($datanav == 01){
						$data['data'][$index][]  = '<a target="_blank" title="View Profile" href="'.Url::toRoute(['/profile/default/index', 'person_id'=>$value['person_id_sender']]).'">'.$value->employee_id_sender.' / '.$value->person_name_sender.'</a>';
						$data['data'][$index][] = Logic::getIndoDate($value->created_time).' '.date('H:i:s', strtotime($value->created_time));
						$data['data'][$index][] = $value->comment;
					}else{
						$data['data'][$index][]  = '<a target="_blank" title="View Profile" href="'.Url::toRoute(['/profile/default/index', 'person_id'=>$value['person_id_sender']]).'">'.$value->employee_id_sender.' / '.$value->person_name_sender.'</a>';
						$data['data'][$index][]  = '<a target="_blank" title="View Profile" href="'.Url::toRoute(['/profile/default/index', 'person_id'=>$value['person_id_approval']]).'">'.$value->employee_id_approval.' / '.$value->person_name_approval.'</a>';
						$data['data'][$index][] = Logic::getIndoDate($value->created_time).' '.date('H:i:s', strtotime($value->created_time));
						$data['data'][$index][] = $value->comment;
						$data['data'][$index][] = $value->justification;
					}
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
	
	public function actionShowformprocess(){
		if(Yii::$app->request->isAjax) {
			try {
				$datanav = strip_tags($_POST['datanav']);
				$approval_id = $_POST['approval_id'];
				$datapengajuan = [];
				if(!empty($approval_id)){
					$model = Approval::find()->andWhere(['in', 'approval_id', $approval_id])->all();
					if(!empty($model)){
						foreach($model as $mdx=>$mrow){
							$datapengajuan[$mrow->apptype_id][] = $mrow->getDatapengajuan(true);
						}
					}else{						
						return Logic::_sendResponse(200, 'Failed', 'Data not found', null, 'application/html');					
					}
					
					// echo '<pre>';
					// var_dump($datapengajuan);exit;
					return	$this->renderPartial('_showformprocess', ['datapengajuan'=>$datapengajuan, 'approval_id'=>$approval_id, 'datanav'=>$datanav]);
				}else{
					return Logic::_sendResponse(200, 'Failed', 'Data not found', null, 'application/html');					
				}
			}catch(\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
			}
		}
	}
	
	public function actionProcessapproval(){
		if (Yii::$app->request->isAjax) {
			try {
				\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				$connection = Yii::$app->db;
				$transaction = $connection->beginTransaction();
				
				$dataaction = strip_tags($_POST['dataaction']);
				$justification = strip_tags($_POST['Approval']['justification']);
				if(!empty($justification)){
					if($dataaction == 'APPROVED' || $dataaction == 'RETURN'){
						$approval_id = json_decode($_POST['approval_id'], true);
						$model = Approval::find()->andWhere(['in', 'approval_id', $approval_id])->all();
						if(!empty($model)){
							foreach($model as $mdx=>$mrow){
								if($dataaction == 'APPROVED' && $mrow->app_status == 'SUBMIT'){
									$datastatus = 'APPROVED';
								}else if($dataaction == 'RETURN' && $mrow->app_status == 'SUBMIT'){
									$datastatus = 'RETURN';	
								}else if($dataaction == 'APPROVED' && $mrow->app_status == 'REQUEST DELETE'){
									$datastatus = 'DELETED';	
								}else if($dataaction == 'RETURN' && $mrow->app_status == 'REQUEST DELETE'){
									$datastatus = 'RETURN';	
								}
								
								$sqlcheckpk = $connection->createCommand('SELECT pg_attribute.attname
								FROM pg_index, pg_class, pg_attribute, pg_namespace 
								WHERE pg_class.oid = \''.$mrow->approvaltype->table_name.'\'::regclass AND indrelid = pg_class.oid AND nspname = \'public\' AND pg_class.relnamespace = pg_namespace.oid AND pg_attribute.attrelid = pg_class.oid AND pg_attribute.attnum = any(pg_index.indkey) AND indisprimary');
								$checkpk = $sqlcheckpk->queryOne();
								
								$sqlupdatepk =  $connection->createCommand('UPDATE '.$mrow->approvaltype->table_name.' SET app_status = :param1 WHERE '.$checkpk['attname'].' = :param2');
								$sqlupdatepk->bindValue(':param1', $datastatus);
								$sqlupdatepk->bindValue(':param2', $mrow->data_id);
								$sqlupdatepk->execute();
								
								$approval = new Approval;
								$approval->company_id = $mrow->company_id;
								$approval->person_id_sender = $mrow->person_id_sender;
								$approval->person_id_approval = Yii::$app->user->identity->employee->person_id;
								$approval->data_id = $mrow->data_id;
								$approval->apptype_id = $mrow->apptype_id;
								$approval->comment = 'Pengajuan telah diproses, SDM memutuskan untuk '.$dataaction.' terkait '.$mrow->comment.'';
								$approval->justification = $justification;
								$approval->app_status = $dataaction;
								$approval->created_by = Yii::$app->user->identity->employee->person_id;
								$approval->created_time = date('Y-m-d H:i:s');
								$approval->save();

								if($mrow->approvaltype->master_id == 142){
									$eGeneral = EmployeeGeneral::findOne($approval->data_id);

									$uEmployee = Employee::findOne($eGeneral->person_id);
									$uEmployee->sex = $eGeneral->sex;
									$uEmployee->ethnic_id = $eGeneral->ethnic_id;
									$uEmployee->ethnic_code = $eGeneral->ethnic_code;
									$uEmployee->ethnic_name = $eGeneral->ethnic_name;
									$uEmployee->ethnic_description = $eGeneral->ethnic_description;
									$uEmployee->religion_id = $eGeneral->religion_id;
									$uEmployee->religion_code = $eGeneral->religion_code;
									$uEmployee->religion_name = $eGeneral->religion_name;
									$uEmployee->religion_description = $eGeneral->religion_description;
									$uEmployee->marital_id = $eGeneral->marital_id;
									$uEmployee->marital_code = $eGeneral->marital_code;
									$uEmployee->marital_name = $eGeneral->marital_name;
									$uEmployee->marital_description = $eGeneral->marital_description;
									$uEmployee->town_of_birth_id = $eGeneral->town_of_birth_id;
									$uEmployee->town_of_birth_code = $eGeneral->town_of_birth_code;
									$uEmployee->town_of_birth_name = $eGeneral->town_of_birth_name;
									$uEmployee->town_of_birth_description = $eGeneral->town_of_birth_description;
									$uEmployee->date_of_birth = $eGeneral->date_of_birth;
									$uEmployee->url_photo = $eGeneral->url_photo;

									$uEmployee->save();
								}

							}
							
							$transaction->commit();
							return Logic::_sendResponse(200, 'Success', 'Pengajuan telah diproses, SDM memutuskan untuk '.$dataaction.'');
						}else{
							$transaction->rollback();
							return Logic::_sendResponse(200, 'Failed', 'Data not found', null, 'application/html');
						}
					}else{					
						$transaction->rollback();
						return Logic::_sendResponse(500, 'Failed', 'Invalid approval type', null, 'application/html');
					}
				}else{
					$transaction->rollback();
					return Logic::_sendResponse(500, 'Failed', 'Justification cannot be blank');
				}	
			}catch(\Exception $e) {
				$transaction->rollback();
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
			}
		}
	}
}
