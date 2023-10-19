<?php

namespace app\modules\project\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Logic;
use app\models\MMaster;
use app\models\Project;
use app\models\ProjectMember;

/**
 * Default controller for the `project` module
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
	
	public function actionLoadproject(){
		if(Yii::$app->request->isAjax) {
			try {
				$datanav = strip_tags($_POST['datanav']);
				$is_done = $datanav == '01' ? false : true;
				
				$datawhere = Project::find();
				$datawhere->andWhere(['is_done'=>$is_done, 'is_active'=>true, 'company_id'=>Yii::$app->user->identity->company_id]);
				$model = $datawhere->all();
				
				return	$this->renderPartial('_loadproject', ['datanav'=>$datanav, 'model'=>$model]);
			}catch(\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
			}
		}
	}
	
	public function actionShowformproject() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
				
                $model = Project::findOne($dataid);
                if (empty($model)) $model = new Project();
				
				$listgroup = MMaster::find()->andWhere(['key'=>'group_project', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				
                return $this->renderPartial('_formproject', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model,
					'listgroup'=>$listgroup
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionCrudproject() {
        if (Yii::$app->request->isAjax) {
            try {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
                $connection = Yii::$app->db;

                if ($dataaction == 'Hapus') {
                    $transaction = $connection->beginTransaction();
                    try {
                        $model = Project::findOne($dataid);
                        if (!empty($model)) {
                            $model->is_active = false;
							$model->deleted_by = Yii::$app->user->identity->employee->person_id;
							$model->deleted_time = date('Y-m-d H:i:s');
                            if ($model->save(false)) {
								$transaction->commit();
                                return Logic::_sendResponse(200, 'Success', 'Project Berhasil dihapus');
                            }
                        } else {
                            return Logic::_sendResponse(404, 'Failed', 'Data not found');
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
						return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/json');
                    }
                } else if ($dataaction == 'Edit' || $dataaction == 'Tambah') {
                    $transaction = $connection->beginTransaction();
                    try {
						$post = Yii::$app->request->post();
                        $model = Project::findOne($dataid);
                        if (empty($model)) {
							$model = new Project();
							$model->load($post);
							$model->created_by = Yii::$app->user->identity->employee->person_id;
							$model->created_time = date('Y-m-d H:i:s');
						}else{
							$model->load($post);
							$model->updated_by = Yii::$app->user->identity->employee->person_id;
							$model->updated_time = date('Y-m-d H:i:s');
						}
                        
						$model->company_id = Yii::$app->user->identity->company_id;
                        $model->company_code = $model->company->code;
                        $model->company_name = $model->company->name;
                        $model->company_description = $model->company->description;
						$model->groupproject_code = $model->groupproject->code;
                        $model->groupproject_name = $model->groupproject->name;
                        $model->groupproject_description = $model->groupproject->description;
                        if ($model->validate()) {
                            if ($model->save()) {
								$project_member = $post['Project']['project_member'];
								if(!empty($project_member)){
									$project_member_not_in = ProjectMember::find()->andWhere(['not in', 'person_id', $project_member])->andWhere(['company_id'=>$model->company_id, 'project_id'=>$model->project_id, 'is_active'=>true])->all();
									if(!empty($project_member_not_in)){
										foreach($project_member_not_in as $pdx=>$prow){
											$prow->is_active = false;
											$prow->save();
										}
									}
									
									foreach($project_member as $pdx=>$prow){
										$member = ProjectMember::find()->andWhere(['company_id'=>$model->company_id, 'project_id'=>$model->project_id, 'person_id'=>$prow, 'is_active'=>true])->one();
										if(empty($member)) $member = new ProjectMember;
										$member->company_id = $model->company_id;
										$member->company_code = $model->company_code;
										$member->company_name = $model->company_name;
										$member->company_description = $model->company_description;
										$member->project_id = $model->project_id;
										$member->project_code = $model->code;
										$member->project_name = $model->name;
										$member->project_description = $model->description;
										$member->person_id = $prow;
										$member->employee_id = $member->person->employee_id;
										$member->person_name = $member->person->person_name;
										$member->is_active = true;
										$member->created_by = Yii::$app->user->identity->employee->person_id;
										$member->created_time = date('Y-m-d H:i:s');
										if($member->validate()){
											$member->save();
											$datasave = true;
										}else{
											$datasave = false;
										}
									}
									
									if($datasave == true){
										$transaction->commit();
										if($dataaction == 'Tambah'){
											$datamessage = 'Project berhasil ditambahkan';
										}else if($dataaction == 'Edit'){
											$datamessage = 'Project berhasil diedit';
										}
										return Logic::_sendResponse(200, 'Success', $datamessage);
									}else{
										$transaction->rollback();
								
										$errors = $member->errors;
										$message = '<ol>';
										foreach ($errors as $edx => $erow) {
											$message .= '<li>' . $erow[0] . '</li>';
										}
										$message .= '</ol>';
										return Logic::_sendResponse(200, 'Failed', $message);		
									}	
								}else{
									$transaction->commit();
									return Logic::_sendResponse(200, 'Success', 'Project berhasil ditambahkan');										
								}
                            } else {
                                $transaction->rollback();
								
                                $errors = $model->errors;
								$message = '<ol>';
								foreach ($errors as $edx => $erow) {
									$message .= '<li>' . $erow[0] . '</li>';
								}
								$message .= '</ol>';
								return Logic::_sendResponse(200, 'Failed', $message);
                            }
                        } else {
                            $transaction->rollback();
							
                            $errors = $model->errors;
                            $message = '<ol>';
                            foreach ($errors as $edx => $erow) {
                                $message .= '<li>' . $erow[0] . '</li>';
                            }
                            $message .= '</ol>';
							return Logic::_sendResponse(200, 'Failed', $message);
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
                        return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/json');
                    }
                } else {
					 return Logic::_sendResponse(404, 'Failed', 'Action tidak ditemukan', null, 'application/json');
                }

                return $data;
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionProjectdetail($project_id)
    {
		$model = Project::findOne($project_id);
		
        return $this->render('project_detail', [
			'model'=>$model
		]);
    }
	
	public function actionLoaddetailproject(){
		if(Yii::$app->request->isAjax) {
			try {
				$project_id = strip_tags($_POST['project_id']);
				$model = Project::findOne($project_id);
				
				$totalmember = ProjectMember::find()->andWhere(['company_id'=>$model->company_id, 'project_id'=>$model->project_id, 'is_active'=>true])->count();
				
				return	$this->renderPartial('_loaddetailproject', ['model'=>$model, 'totalmember'=>$totalmember]);
			}catch(\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
			}
		}
	}
	
	public function actionLoaddetailmembers(){
		if(Yii::$app->request->isAjax) {
			try {
				$project_id = strip_tags($_POST['project_id']);
				$model = Project::findOne($project_id);
				
				$model = ProjectMember::find()->andWhere(['company_id'=>$model->company_id, 'project_id'=>$model->project_id, 'is_active'=>true])->all();
				
				return	$this->renderPartial('_loaddetailmembers', ['model'=>$model]);
			}catch(\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
			}
		}
	}
}
