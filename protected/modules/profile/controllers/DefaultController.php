<?php

namespace app\modules\profile\controllers;

use Yii;
use yii\web\Controller;
use app\models\Employee;
use app\models\EmployeeTraining;
use app\models\EmployeeEducation;
use app\models\EmployeeFamily;
use app\models\EmployeeIdentity;
use app\models\EmployeeContact;
use app\models\EmployeeAddress;
use app\models\EmployeeGeneral;
use app\models\EmployeeInnovation;
use app\models\MMaster;
use app\models\Approval;
use app\components\Logic;
use app\components\Hierarki;
use yii\web\UploadedFile;
use yii\helpers\Url;
use kartik\mpdf\Pdf;
use Cocur\Slugify\Slugify;

/**
 * Default controller for the `profile` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
		// echo '<pre>';
		// var_dump(Yii::$app->user->identity->employee->person_name);exit;
		if($_GET['person_id'] != ''){
			Yii::$app->session->set('person_id', (int)$_GET['person_id']);
			$person_id = Yii::$app->session->get('person_id');
		}else{
			Yii::$app->session->set('person_id', (int)Yii::$app->user->identity->employee->person_id);
			$person_id = Yii::$app->session->get('person_id');
		}
		
		$model = Employee::findOne($person_id);
		
        return $this->render('index', [
			'model'=>$model
		]);
    }
	
	public function actionShowannouncement() {
        if (Yii::$app->request->isAjax) {
            try {
                #perbaikan sahrul
				$datawhere = MMaster::find();
				$datawhere->andWhere(LTRIM($search, ' AND '), $params);
				$datawhere->andWhere(['key'=>'announcement_profile', 'is_active'=>Logic::statusActive()]);
				$datawhere->andWhere(['or', ['master_id'=>Yii::$app->user->identity->employee->company_id], ['parent_id'=>Yii::$app->user->identity->employee->company_id]]);
				
				$model = $datawhere->one();
				
                return $this->renderPartial('_pemberitahuan', [
					'model'=>$model
				]);
            } catch (\Exception $e) {
               return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoadgeneral() {
        if (Yii::$app->request->isAjax) {
            try {
                $person_id = Yii::$app->session->get('person_id');
                $atasan = Hierarki::getHead($person_id)['data'];

                $person_age = 'extract(year from age(now(), date_of_birth)) || \' Tahun\' || \' \' || extract(month from age(now(), date_of_birth)) || \' Bulan\' as person_age';
                $employee = Employee::find()->select(['*', $person_age])->where(['person_id' => $person_id])->one();
				
				$identitas = new EmployeeIdentity;
				$kontak = new EmployeeContact;
				$alamat = new EmployeeAddress;
				
                return $this->renderPartial('general/_general', ['employee' => $employee, 'atasan' => $atasan, 'identitas'=>$identitas, 'kontak'=>$kontak, 'alamat'=>$alamat]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoaddtidentitas() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$person_id = Yii::$app->session->get('person_id');
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];

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
                  
					if ($value->app_status != 'ON PROGRESS') {
						$data['data'][$index][] = '<button onclick="showformidentitas(' . $value->identity_id . ', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button><button onclick="showformidentitas(' . $value->identity_id . ', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
					}else{
						$data['data'][$index][] = '-';
					}
					
                    $data['data'][$index][] = '<button onclick="showpengajuan(' . $value->identity_id . ', \'identitas\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->app_status).'</button>';
                    $data['data'][$index][] = $value->identitytype->name;
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

            return $data;
        }
    }
	
	public function actionShowformidentitas() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
				$person_id = Yii::$app->session->get('person_id');
				
                $model = EmployeeIdentity::findOne($dataid);
                if (empty($model)) $model = new EmployeeIdentity();
				
				$listidentitas = MMaster::find()->andWhere(['key'=>'identity_type', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();
				
                return $this->renderPartial('general/_formidentitas', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model,
					'listidentitas'=>$listidentitas
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionCrudidentitas() {
        if (Yii::$app->request->isAjax) {
            try {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $person_id = Yii::$app->session->get('person_id');
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
                $connection = Yii::$app->db;

                if ($dataaction == 'Hapus') {
                    $transaction = $connection->beginTransaction();
                    try {
                        $model = EmployeeIdentity::findOne($dataid);
                        if (!empty($model)) {
                            $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                            if ($model->save(false)) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->identity_id;
                                $approval->apptype_id = EmployeeIdentity::APP_TYPE;
                                $approval->comment = 'Pengajuan penghapusan data Informasi Identitas';
                                $approval->created_by = $person_id;
                                $approval->app_status = Logic::APP_STATUS_REQUEST_DELETE;
                                $approval->created_time = date("Y-m-d H:i:s");
                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
                                        $data['code'] = 200;
                                        $data['status'] = 'Success';
                                        $data['message'] = 'Pengajuan sedang dalam proses verifikasi oleh SDM';
                                    }
                                } else {
                                    $errors = $approval->errors;
                                    $data['code'] = 200;
                                    $data['status'] = 'Failed';
                                    $data['message'] = '<ol>';
                                    foreach ($errors as $edx => $erow) {
                                        $data['message'] .= '<li>' . $erow[0] . '</li>';
                                    }
                                    $data['message'] .= '</ol>';
                                }
                            }
                        } else {
                            $data['code'] = 404;
                            $data['status'] = 'Failed';
                            $data['message'] = 'Data tidak ditemukan';
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
                        $data['code'] = 404;
                        $data['status'] = 'Failed';
                        $data['message'] = Logic::Exceptionjson('Terjadi kesalahan! Silahkan hubungi administrator');
                    }
                } else if ($dataaction == 'Edit' || $dataaction == 'Tambah') {
                    $transaction = $connection->beginTransaction();
                    try {
						$post = Yii::$app->request->post();
                        $model = EmployeeIdentity::findOne($dataid);
                        if (empty($model)) {
							$model = new EmployeeIdentity();
							$model->load($post);
							$model->created_by = $person_id;
							$model->created_time = date('Y-m-d H:i:s');
						}else{
							$old_file = $model->url_scan_identity;
							$model->load($post);
							$model->updated_by = $person_id;
							$model->updated_time = date('Y-m-d H:i:s');
						}
                        
						$model->company_id = Yii::$app->user->identity->company_id;
                        $model->company_code = $model->company->code;
                        $model->company_name = $model->company->name;
                        $model->company_description = $model->company->description;
                        $model->person_id = $person_id;
                        $model->employee_id = $model->person->employee_id;
                        $model->person_name = $model->person->person_name;
						$model->identitytype_code = $model->identitytype->code;
                        $model->identitytype_name = $model->identitytype->name;
                        $model->identitytype_description = $model->identitytype->description;
                        $model->data_file = UploadedFile::getInstance($model, 'url_scan_identity');
                        $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                        if ($model->upload($old_file, $person_id)['data'] == true) {
                            if ($model->save()) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->identity_id;
                                $approval->apptype_id = EmployeeIdentity::APP_TYPE;
                                $approval->comment = 'Pengajuan penambahan atau perubahan data Informasi Identitas';
                                $approval->app_status = Logic::APP_STATUS_SUBMIT;
                                $approval->created_by = $person_id;
                                $approval->created_time = date("Y-m-d H:i:s");

                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
										return Logic::_sendResponse(200, 'Success', 'Pengajuan sedang dalam proses verifikasi oleh SDM');
                                    }
                                } else {
                                    $transaction->rollback();
									
									$errors = $approval->errors;
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
                        } else {
                            $transaction->rollback();
							
                            $errors = $model->upload()['errors'];
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
	
	public function actionLoaddtkontak() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$person_id = Yii::$app->session->get('person_id');
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];

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
			
			$datawhere = EmployeeContact::find();
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
                  
					if ($value->app_status != 'ON PROGRESS') {
						$data['data'][$index][] = '<button onclick="showformkontak(' . $value->contact_id . ', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button><button onclick="showformkontak(' . $value->contact_id . ', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
					}else{
						$data['data'][$index][] = '-';
					}
					
                    $data['data'][$index][] = '<button onclick="showpengajuan(' . $value->contact_id . ', \'kontak\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->app_status).'</button>';
                    $data['data'][$index][] = $value->contacttype->name;
                    $data['data'][$index][] = strip_tags($value->no_contact);
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
	
	public function actionShowformkontak() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
				$person_id = Yii::$app->session->get('person_id');
				
                $model = EmployeeContact::findOne($dataid);
                if (empty($model)) $model = new EmployeeContact();
				
				$listkontak = MMaster::find()->andWhere(['key'=>'contact_person', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();
				
                return $this->renderPartial('general/_formkontak', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model,
					'listkontak'=>$listkontak
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionCrudkontak() {
        if (Yii::$app->request->isAjax) {
            try {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $person_id = Yii::$app->session->get('person_id');
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
                $connection = Yii::$app->db;

                if ($dataaction == 'Hapus') {
                    $transaction = $connection->beginTransaction();
                    try {
                        $model = EmployeeContact::findOne($dataid);
                        if (!empty($model)) {
                            $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                            if ($model->save(false)) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->contact_id;
                                $approval->apptype_id = EmployeeContact::APP_TYPE;
                                $approval->comment = 'Pengajuan penghapusan data Informasi Kontak';
                                $approval->created_by = $person_id;
                                $approval->app_status = Logic::APP_STATUS_REQUEST_DELETE;
                                $approval->created_time = date("Y-m-d H:i:s");
                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
                                        $data['code'] = 200;
                                        $data['status'] = 'Success';
                                        $data['message'] = 'Pengajuan sedang dalam proses verifikasi oleh SDM';
                                    }
                                } else {
                                    $errors = $approval->errors;
                                    $data['code'] = 200;
                                    $data['status'] = 'Failed';
                                    $data['message'] = '<ol>';
                                    foreach ($errors as $edx => $erow) {
                                        $data['message'] .= '<li>' . $erow[0] . '</li>';
                                    }
                                    $data['message'] .= '</ol>';
                                }
                            }
                        } else {
                            $data['code'] = 404;
                            $data['status'] = 'Failed';
                            $data['message'] = 'Data tidak ditemukan';
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
                        $data['code'] = 404;
                        $data['status'] = 'Failed';
                        $data['message'] = Logic::Exceptionjson('Terjadi kesalahan! Silahkan hubungi administrator');
                    }
                } else if ($dataaction == 'Edit' || $dataaction == 'Tambah') {
                    $transaction = $connection->beginTransaction();
                    try {
						$post = Yii::$app->request->post();
                        $model = EmployeeContact::findOne($dataid);
                        if (empty($model)) {
							$model = new EmployeeContact();
							$model->load($post);
							$model->created_by = $person_id;
							$model->created_time = date('Y-m-d H:i:s');
						}else{
							$model->load($post);
							$model->updated_by = $person_id;
							$model->updated_time = date('Y-m-d H:i:s');
						}
                        
						$model->company_id = Yii::$app->user->identity->company_id;
                        $model->company_code = $model->company->code;
                        $model->company_name = $model->company->name;
                        $model->company_description = $model->company->description;
                        $model->person_id = $person_id;
                        $model->employee_id = $model->person->employee_id;
                        $model->person_name = $model->person->person_name;
						$model->contacttype_code = $model->contacttype->code;
                        $model->contacttype_name = $model->contacttype->name;
                        $model->contacttype_description = $model->contacttype->description;
                        $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                        if ($model->validate()) {
                            if ($model->save()) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->contact_id;
                                $approval->apptype_id = EmployeeContact::APP_TYPE;
                                $approval->comment = 'Pengajuan penambahan atau perubahan data Informasi Kontak';
                                $approval->app_status = Logic::APP_STATUS_SUBMIT;
                                $approval->created_by = $person_id;
                                $approval->created_time = date("Y-m-d H:i:s");

                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
										return Logic::_sendResponse(200, 'Success', 'Pengajuan sedang dalam proses verifikasi oleh SDM');
                                    }
                                } else {
                                    $transaction->rollback();
									
									$errors = $approval->errors;
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
	
	public function actionLoaddtalamat() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$person_id = Yii::$app->session->get('person_id');
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];

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
			
			$datawhere = EmployeeAddress::find();
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
                  
					if ($value->app_status = 'ON PROGRESS') {
						$data['data'][$index][] = '<button onclick="showformalamat(' . $value->address_id . ', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button><button onclick="showformalamat(' . $value->address_id . ', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
					}else{
						$data['data'][$index][] = '-';
					}
					
                    $data['data'][$index][] = '<button onclick="showpengajuan(' . $value->address_id . ', \'alamat\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->app_status).'</button>';
                    $data['data'][$index][] = $value->location->name;
                    $data['data'][$index][] = strip_tags($value->address);
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
	
	public function actionShowformalamat() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
				$person_id = Yii::$app->session->get('person_id');
				
                $model = EmployeeAddress::findOne($dataid);
                if (empty($model)) $model = new EmployeeAddress();
				//$listalamat = MMaster::find()->andWhere(['key'=>'provinsi', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->employee->company_id], ['parent_id'=>Yii::$app->user->identity->employee->company_id]])->all();
                // var_dump($listalamat); exit;
				//$listkabupaten = MMaster::find()->andWhere(['key'=>'kabupaten', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->employee->company_id], ['parent_id'=>Yii::$app->user->identity->employee->company_id]])->all();
                return $this->renderPartial('general/_formalamat', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model,
                    //'listalamat'=>$listalamat,
                    //'listkabupaten'=>$listkabupaten,
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionCrudalamat() {
        if (Yii::$app->request->isAjax) {
            try {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $person_id = Yii::$app->session->get('person_id');
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
                $connection = Yii::$app->db;

                if ($dataaction == 'Hapus') {
                    $transaction = $connection->beginTransaction();
                    try {
                        $model = EmployeeAddress::findOne($dataid);
                        if (!empty($model)) {
                            $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                            if ($model->save(false)) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->address_id;
                                $approval->apptype_id = EmployeeAddress::APP_TYPE;
                                $approval->comment = 'Pengajuan penghapusan data Informasi Alamaat';
                                $approval->created_by = $person_id;
                                $approval->app_status = Logic::APP_STATUS_REQUEST_DELETE;
                                $approval->created_time = date("Y-m-d H:i:s");
                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
                                        $data['code'] = 200;
                                        $data['status'] = 'Success';
                                        $data['message'] = 'Pengajuan sedang dalam proses verifikasi oleh SDM';
                                    }
                                } else {
                                    $errors = $approval->errors;
                                    $data['code'] = 200;
                                    $data['status'] = 'Failed';
                                    $data['message'] = '<ol>';
                                    foreach ($errors as $edx => $erow) {
                                        $data['message'] .= '<li>' . $erow[0] . '</li>';
                                    }
                                    $data['message'] .= '</ol>';
                                }
                            }
                        } else {
                            $data['code'] = 404;
                            $data['status'] = 'Failed';
                            $data['message'] = 'Data tidak ditemukan';
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
                        $data['code'] = 404;
                        $data['status'] = 'Failed';
                        $data['message'] = Logic::Exceptionjson('Terjadi kesalahan! Silahkan hubungi administrator');
                    }
                } else if ($dataaction == 'Edit' || $dataaction == 'Tambah') {
                    $transaction = $connection->beginTransaction();
                    try {
						$post = Yii::$app->request->post();
                        $model = EmployeeAddress::findOne($dataid);
                        if (empty($model)) {
							$model = new EmployeeAddress();
							$model->load($post);
							$model->created_by = $person_id;
							$model->created_time = date('Y-m-d H:i:s');
						}else{
							$model->load($post);
							$model->updated_by = $person_id;
							$model->updated_time = date('Y-m-d H:i:s');
						}
                        
						$model->company_id = Yii::$app->user->identity->company_id;
                        $model->company_code = $model->company->code;
                        $model->company_name = $model->company->name;
                        $model->company_description = $model->company->description;
                        $model->person_id = $person_id;
                        $model->employee_id = $model->person->employee_id;
                        $model->person_name = $model->person->person_name;
						$model->location_code = $model->location->code;
                        $model->location_name = $model->location->name;
                        $model->location_description = $model->location->description;
                        $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                        if ($model->validate()) {
                            if ($model->save()) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->address_id;
                                $approval->apptype_id = EmployeeAddress::APP_TYPE;
                                $approval->comment = 'Pengajuan penambahan atau perubahan data Informasi Alamat';
                                $approval->app_status = Logic::APP_STATUS_SUBMIT;
                                $approval->created_by = $person_id;
                                $approval->created_time = date("Y-m-d H:i:s");

                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
										return Logic::_sendResponse(200, 'Success', 'Pengajuan sedang dalam proses verifikasi oleh SDM');
                                    }
                                } else {
                                    $transaction->rollback();
									
									$errors = $approval->errors;
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
	
	public function actionLoadjabatan() {
        if (Yii::$app->request->isAjax) {
            try {
                $person_id = Yii::$app->session->get('person_id');
                
                return $this->renderPartial('jabatan/_jabatan');
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoadpelatihan() {
        if (Yii::$app->request->isAjax) {
            try {
                $person_id = Yii::$app->session->get('person_id');
                $model = new EmployeeTraining;
				
				$listpelatihan = MMaster::find()->andWhere(['key'=>'training_group', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				
                return $this->renderPartial('pelatihan/_pelatihan', ['model' => $model, 'listpelatihan'=>$listpelatihan]);
            } catch (\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoaddtpelatihan() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$person_id = Yii::$app->session->get('person_id');
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];

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
			
			$datawhere = EmployeeTraining::find();
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
                  
					if ($value->app_status != 'ON PROGRESS') {
						$data['data'][$index][] = '<button onclick="showformpelatihan(' . $value->td_id . ', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button><button onclick="showformpelatihan(' . $value->td_id . ', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
					}else{
						$data['data'][$index][] = '-';
					}
					
                    $data['data'][$index][] = '<button onclick="showpengajuan(' . $value->td_id . ', \'pelatihan\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->app_status).'</button>';
                    $data['data'][$index][] = Logic::getIndoDate($value->start_of_training);
                    $data['data'][$index][] = Logic::getIndoDate($value->end_of_training);
                    $data['data'][$index][] = strip_tags($value->trg_name);
                    $data['data'][$index][] = strip_tags($value->location);
                    $data['data'][$index][] = strip_tags($value->title);
                    $data['data'][$index][] = strip_tags($value->institute);
                    $data['data'][$index][] = strip_tags($value->no_certification);
                    $data['data'][$index][] = file_exists(\Yii::getAlias('@webroot').$value->url_scan_certification) && $value->url_scan_certification ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $value->url_scan_certification]) . '"><i class="icofont icofont-file-document"></i></a>' : '';
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
	
	public function actionShowformpelatihan() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
				$person_id = Yii::$app->session->get('person_id');
				
                $model = EmployeeTraining::findOne($dataid);
                if (empty($model)) $model = new EmployeeTraining();
				
				$listpelatihan = MMaster::find()->andWhere(['key'=>'training_group', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();
				
                return $this->renderPartial('pelatihan/_formpelatihan', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model,
					'listpelatihan'=>$listpelatihan
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionCrudpelatihan() {
        if (Yii::$app->request->isAjax) {
            try {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $person_id = Yii::$app->session->get('person_id');
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
                $connection = Yii::$app->db;

                if ($dataaction == 'Hapus') {
                    $transaction = $connection->beginTransaction();
                    try {
                        $model = EmployeeTraining::findOne($dataid);
                        if (!empty($model)) {
                            $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                            if ($model->save(false)) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->td_id;
                                $approval->apptype_id = EmployeeTraining::APP_TYPE;
                                $approval->comment = 'Pengajuan penghapusan data Pelatihan';
                                $approval->created_by = $person_id;
                                $approval->app_status = Logic::APP_STATUS_REQUEST_DELETE;
                                $approval->created_time = date("Y-m-d H:i:s");
                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
                                        $data['code'] = 200;
                                        $data['status'] = 'Success';
                                        $data['message'] = 'Pengajuan sedang dalam proses verifikasi oleh SDM';
                                    }
                                } else {
                                    $errors = $approval->errors;
                                    $data['code'] = 200;
                                    $data['status'] = 'Failed';
                                    $data['message'] = '<ol>';
                                    foreach ($errors as $edx => $erow) {
                                        $data['message'] .= '<li>' . $erow[0] . '</li>';
                                    }
                                    $data['message'] .= '</ol>';
                                }
                            }
                        } else {
                            $data['code'] = 404;
                            $data['status'] = 'Failed';
                            $data['message'] = 'Data tidak ditemukan';
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
                        $data['code'] = 404;
                        $data['status'] = 'Failed';
                        $data['message'] = Logic::Exceptionjson('Terjadi kesalahan! Silahkan hubungi administrator');
                    }
                } else if ($dataaction == 'Edit' || $dataaction == 'Tambah') {
                    $transaction = $connection->beginTransaction();
                    try {
						$post = Yii::$app->request->post();
                        $model = EmployeeTraining::findOne($dataid);
                        if (empty($model)) {
							$model = new EmployeeTraining();
							$model->load($post);
							$model->created_by = $person_id;
							$model->created_time = date('Y-m-d H:i:s');
						}else{
							$old_file = $model->url_scan_certification;
							$model->load($post);
							$model->updated_by = $person_id;
							$model->updated_time = date('Y-m-d H:i:s');
						}
                        
						$tgl_range_pelatihan = $post['EmployeeTraining']['tgl_range_pelatihan'];
						if(!empty($tgl_range_pelatihan)){
							$exp_trp = explode(' to ', $tgl_range_pelatihan);
							if(count($exp_trp) > 1){
								$start_of_training = $exp_trp[0];
								$end_of_training = $exp_trp[1];
							}else{
								$start_of_training = $exp_trp[0];
								$end_of_training = $exp_trp[0];
							}
						}
						
						$model->start_of_training = $start_of_training;
						$model->end_of_training = $end_of_training;
						$model->company_id = Yii::$app->user->identity->company_id;
                        $model->company_code = $model->company->code;
                        $model->company_name = $model->company->name;
                        $model->company_description = $model->company->description;
                        $model->person_id = $person_id;
                        $model->employee_id = $model->person->employee_id;
                        $model->person_name = $model->person->person_name;
						$model->trg_code = $model->traininggroup->code;
                        $model->trg_name = $model->traininggroup->name;
                        $model->trg_description = $model->traininggroup->description;
                        $model->data_file = UploadedFile::getInstance($model, 'url_scan_certification');
                        $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                        if ($model->upload($old_file, $person_id)['data'] == true) {
                            if ($model->save()) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->td_id;
                                $approval->apptype_id = EmployeeTraining::APP_TYPE;
                                $approval->comment = 'Pengajuan penambahan atau perubahan data Pelatihan';
                                $approval->app_status = Logic::APP_STATUS_SUBMIT;
                                $approval->created_by = $person_id;
                                $approval->created_time = date("Y-m-d H:i:s");

                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
										return Logic::_sendResponse(200, 'Success', 'Pengajuan sedang dalam proses verifikasi oleh SDM');
                                    }
                                } else {
                                    $transaction->rollback();
									
									$errors = $approval->errors;
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
                        } else {
                            $transaction->rollback();
							
                            $errors = $model->upload()['errors'];
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
	
	public function actionShowpengajuan() {
        if (Yii::$app->request->isAjax) {
            try {
				$dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = strip_tags($_POST['dataaction']);
				$person_id = Yii::$app->session->get('person_id');
				
				$datawhere = Approval::find();
				$datawhere->andWhere(['person_id_sender'=>$person_id, 'data_id'=>$dataid]);
				if($dataaction == 'pelatihan'){
					$datawhere->andWhere(['apptype_id'=>EmployeeTraining::APP_TYPE]);
				}else if($dataaction == 'pendidikan'){
					$datawhere->andWhere(['apptype_id'=>EmployeeEducation::APP_TYPE]);
				}else if($dataaction == 'keluarga'){
					$datawhere->andWhere(['apptype_id'=>EmployeeFamily::APP_TYPE]);
				}else if($dataaction == 'innovation'){
					$datawhere->andWhere(['apptype_id'=>EmployeeInnovation::APP_TYPE]);
                }else if($dataaction == 'identitas'){
					$datawhere->andWhere(['apptype_id'=>EmployeeIdentity::APP_TYPE]);
				}else if($dataaction == 'kontak'){
					$datawhere->andWhere(['apptype_id'=>EmployeeContact::APP_TYPE]);
				}else if($dataaction == 'alamat'){
					$datawhere->andWhere(['apptype_id'=>EmployeeAddress::APP_TYPE]);
				}else if($dataaction == 'general'){
                    $datawhere->andWhere(['apptype_id'=>EmployeeGeneral::APP_TYPE]);
                }
				
				$model = $datawhere->orderBy(['created_time'=>SORT_DESC])->all();
                
                return $this->renderPartial('_historypengajuan', [
					'model'=>$model
				]);
            } catch (\Exception $e) {
               return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoadpendidikan() {
        if (Yii::$app->request->isAjax) {
            try {
                $person_id = Yii::$app->session->get('person_id');
                $model = new EmployeeEducation;
				
				$listpendidikan = MMaster::find()->andWhere(['key'=>'education_level', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				
                return $this->renderPartial('pendidikan/_pendidikan', ['model' => $model, 'listpendidikan'=>$listpendidikan]);
            } catch (\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoaddtpendidikan() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$person_id = Yii::$app->session->get('person_id');
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];

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
			
			$datawhere = EmployeeEducation::find();
			$datawhere->andWhere('person_id = :person_id AND app_status != :app_status', [':person_id' => $person_id, ':app_status' => 'DELETED']);
            $datawhere->andWhere(LTRIM($search, ' AND '), $params);
			
            $datacount = $datawhere->count();
            $dataall = $datawhere->limit($length)->offset($start)->all();
			
            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
					// var_dump(file_exists(\Yii::getAlias('@webroot').$value->url_scan_identity));
					// var_dump(\Yii::getAlias('@webroot'));
					// var_dump($value->url_scan_identity);
                    $data['draw'] = $_POST['draw'];
                    $data['recordsTotal'] = $datacount;
                    $data['recordsFiltered'] = $datacount;
                    $data['start'] = $start;
                    $data['length'] = $length;
                    $data['data'][$index]['DT_RowId'] = $i;
                    $data['data'][$index][] = $start + $i;
                  
					if ($value->app_status != 'ON PROGRESS') {
						$data['data'][$index][] = '<button onclick="showformpendidikan(' . $value->edu_id . ', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button><button onclick="showformpendidikan(' . $value->edu_id . ', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
					}else{
						$data['data'][$index][] = '-';
					}
					
                    $data['data'][$index][] = '<button onclick="showpengajuan(' . $value->edu_id . ', \'pendidikan\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->app_status).'</button>';
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

            return $data;
        }
    }
	
	public function actionShowformpendidikan() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
				$person_id = Yii::$app->session->get('person_id');
				
                $model = EmployeeEducation::findOne($dataid);
                if (empty($model)) $model = new EmployeeEducation();
				
				$listpendidikan = MMaster::find()->andWhere(['key'=>'education_level', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();
				
                return $this->renderPartial('pendidikan/_formpendidikan', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model,
					'listpendidikan'=>$listpendidikan
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionCrudpendidikan() {
        if (Yii::$app->request->isAjax) {
            try {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $person_id = Yii::$app->session->get('person_id');
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
                $connection = Yii::$app->db;

                if ($dataaction == 'Hapus') {
                    $transaction = $connection->beginTransaction();
                    try {
                        $model = EmployeeEducation::findOne($dataid);
                        if (!empty($model)) {
                            $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                            if ($model->save(false)) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->edu_id;
                                $approval->apptype_id = EmployeeEducation::APP_TYPE;
                                $approval->comment = 'Pengajuan penghapusan data Pendidikan';
                                $approval->created_by = $person_id;
                                $approval->app_status = Logic::APP_STATUS_REQUEST_DELETE;
                                $approval->created_time = date("Y-m-d H:i:s");
                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
                                        $data['code'] = 200;
                                        $data['status'] = 'Success';
                                        $data['message'] = 'Pengajuan sedang dalam proses verifikasi oleh SDM';
                                    }
                                } else {
                                    $errors = $approval->errors;
                                    $data['code'] = 200;
                                    $data['status'] = 'Failed';
                                    $data['message'] = '<ol>';
                                    foreach ($errors as $edx => $erow) {
                                        $data['message'] .= '<li>' . $erow[0] . '</li>';
                                    }
                                    $data['message'] .= '</ol>';
                                }
                            }
                        } else {
                            $data['code'] = 404;
                            $data['status'] = 'Failed';
                            $data['message'] = 'Data tidak ditemukan';
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
                        $data['code'] = 404;
                        $data['status'] = 'Failed';
                        $data['message'] = Logic::Exceptionjson('Terjadi kesalahan! Silahkan hubungi administrator');
                    }
                } else if ($dataaction == 'Edit' || $dataaction == 'Tambah') {
                    $transaction = $connection->beginTransaction();
                    try {
						$post = Yii::$app->request->post();
                        $model = EmployeeEducation::findOne($dataid);
                        if (empty($model)) {
							$model = new EmployeeEducation();
							$model->load($post);
							$model->created_by = $person_id;
							$model->created_time = date('Y-m-d H:i:s');
						}else{
							$old_file = $model->url_scan_identity;
							$model->load($post);
							$model->updated_by = $person_id;
							$model->updated_time = date('Y-m-d H:i:s');
						}
                        
						$model->company_id = Yii::$app->user->identity->company_id;
                        $model->company_code = $model->company->code;
                        $model->company_name = $model->company->name;
                        $model->company_description = $model->company->description;
                        $model->person_id = $person_id;
                        $model->employee_id = $model->person->employee_id;
                        $model->person_name = $model->person->person_name;
						$model->level_code = $model->educationlevel->code;
                        $model->level_name = $model->educationlevel->name;
                        $model->level_description = $model->educationlevel->description;
                        $model->data_file = UploadedFile::getInstance($model, 'url_scan_identity');
                        $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                        if ($model->upload($old_file, $person_id)['data'] == true) {
                            if ($model->save()) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->edu_id;
                                $approval->apptype_id = EmployeeEducation::APP_TYPE;
                                $approval->comment = 'Pengajuan penambahan atau perubahan data Pendidikan';
                                $approval->app_status = Logic::APP_STATUS_SUBMIT;
                                $approval->created_by = $person_id;
                                $approval->created_time = date("Y-m-d H:i:s");

                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
										return Logic::_sendResponse(200, 'Success', 'Pengajuan sedang dalam proses verifikasi oleh SDM');
                                    }
                                } else {
                                    $transaction->rollback();
									
									$errors = $approval->errors;
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
                        } else {
                            $transaction->rollback();
							
                            $errors = $model->upload()['errors'];
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
	
	public function actionLoadkeluarga() {
        if (Yii::$app->request->isAjax) {
            try {
                $person_id = Yii::$app->session->get('person_id');
                $model = new EmployeeFamily;
				
				$listtype = MMaster::find()->andWhere(['key'=>'family_type', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				$liststatus = MMaster::find()->andWhere(['key'=>'family_status', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				$listprofession = MMaster::find()->andWhere(['key'=>'profession', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				
                return $this->renderPartial('keluarga/_keluarga', ['model' => $model, 'listtype'=>$listtype, 'liststatus'=>$liststatus, 'listprofession'=>$listprofession]);
            } catch (\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoaddtkeluarga() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$person_id = Yii::$app->session->get('person_id');
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];

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
			
			$datawhere = EmployeeFamily::find();
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
                  
					if ($value->app_status != 'ON PROGRESS') {
						$data['data'][$index][] = '<button onclick="showformkeluarga(' . $value->family_id . ', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button><button onclick="showformkeluarga(' . $value->family_id . ', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
					}else{
						$data['data'][$index][] = '-';
					}
					
                    $data['data'][$index][] = '<button onclick="showpengajuan(' . $value->family_id . ', \'keluarga\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->app_status).'</button>';
                    $data['data'][$index][] = strip_tags($value->famtype_name);
                    $data['data'][$index][] = strip_tags($value->famstatus_name);
                    $data['data'][$index][] = strip_tags($value->name);
                    $data['data'][$index][] = $value->sex == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN';
                    $data['data'][$index][] = Logic::getIndoDate($value->date_of_birth);
                    $data['data'][$index][] = strip_tags($value->town_of_birth_name);
                    $data['data'][$index][] = strip_tags($value->profession_name);
                    $data['data'][$index][] = $value->is_still_alive ? 'YES' : 'NO';
                    $data['data'][$index][] = $value->is_dependent ? 'YES' : 'NO';
                    $data['data'][$index][] = Logic::getIndoDate($value->date_of_marital);
                    $data['data'][$index][] = Logic::getIndoDate($value->date_of_divorce);
                    $data['data'][$index][] = Logic::getIndoDate($value->date_of_dead);
                    $data['data'][$index][] = strip_tags($value->no_marital);
                    $data['data'][$index][] = file_exists(\Yii::getAlias('@webroot').$value->url_scan_marital) && $value->url_scan_marital ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $value->url_scan_marital]) . '"><i class="icofont icofont-file-document"></i></a>' : '';
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
	
	public function actionShowformkeluarga() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
				$person_id = Yii::$app->session->get('person_id');
				
                $model = EmployeeFamily::findOne($dataid);
                if (empty($model)) $model = new EmployeeFamily();
				
				$listtype = MMaster::find()->andWhere(['key'=>'family_type', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				$liststatus = MMaster::find()->andWhere(['key'=>'family_status', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				$listprofession = MMaster::find()->andWhere(['key'=>'profession', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
				
                return $this->renderPartial('keluarga/_formkeluarga', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model,
					'listtype'=>$listtype,
					'liststatus'=>$liststatus,
					'listprofession'=>$listprofession
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionCrudkeluarga() {
        if (Yii::$app->request->isAjax) {
            try {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $person_id = Yii::$app->session->get('person_id');
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
                $connection = Yii::$app->db;

                if ($dataaction == 'Hapus') {
                    $transaction = $connection->beginTransaction();
                    try {
                        $model = EmployeeFamily::findOne($dataid);
                        if (!empty($model)) {
                            $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                            if ($model->save(false)) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->family_id;
                                $approval->apptype_id = EmployeeFamily::APP_TYPE;
                                $approval->comment = 'Pengajuan penghapusan data Keluarga';
                                $approval->created_by = $person_id;
                                $approval->app_status = Logic::APP_STATUS_REQUEST_DELETE;
                                $approval->created_time = date("Y-m-d H:i:s");
                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
                                        $data['code'] = 200;
                                        $data['status'] = 'Success';
                                        $data['message'] = 'Pengajuan sedang dalam proses verifikasi oleh SDM';
                                    }
                                } else {
                                    $errors = $approval->errors;
                                    $data['code'] = 200;
                                    $data['status'] = 'Failed';
                                    $data['message'] = '<ol>';
                                    foreach ($errors as $edx => $erow) {
                                        $data['message'] .= '<li>' . $erow[0] . '</li>';
                                    }
                                    $data['message'] .= '</ol>';
                                }
                            }
                        } else {
                            $data['code'] = 404;
                            $data['status'] = 'Failed';
                            $data['message'] = 'Data tidak ditemukan';
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
                        $data['code'] = 404;
                        $data['status'] = 'Failed';
                        $data['message'] = Logic::Exceptionjson('Terjadi kesalahan! Silahkan hubungi administrator');
                    }
                } else if ($dataaction == 'Edit' || $dataaction == 'Tambah') {
                    $transaction = $connection->beginTransaction();
                    try {
						$post = Yii::$app->request->post();
                        $model = EmployeeFamily::findOne($dataid);
                        if (empty($model)) {
							$model = new EmployeeFamily();
							$model->load($post);
							$model->created_by = $person_id;
							$model->created_time = date('Y-m-d H:i:s');
						}else{
							$old_file = $model->url_scan_marital;
							$model->load($post);
							$model->updated_by = $person_id;
							$model->updated_time = date('Y-m-d H:i:s');
						}
                        
						$model->company_id = Yii::$app->user->identity->company_id;
                        $model->company_code = $model->company->code;
                        $model->company_name = $model->company->name;
                        $model->company_description = $model->company->description;
                        $model->person_id = $person_id;
                        $model->employee_id = $model->person->employee_id;
                        $model->person_name = $model->person->person_name;
						$model->famtype_code = $model->familytype->code;
                        $model->famtype_name = $model->familytype->name;
                        $model->famtype_description = $model->familytype->description;
						$model->famstatus_code = $model->familystatus->code;
                        $model->famstatus_name = $model->familystatus->name;
                        $model->famstatus_description = $model->familystatus->description;
						$model->profession_code = $model->profession->code;
                        $model->profession_name = $model->profession->name;
                        $model->profession_description = $model->profession->description;
						$model->town_of_birth_code = $model->town->code;
                        $model->town_of_birth_name = $model->town->name;
                        $model->town_of_birth_description = $model->town->description;
                        $model->data_file = UploadedFile::getInstance($model, 'url_scan_marital');
                        $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                        if ($model->upload($old_file, $person_id)['data'] == true) {
                            if ($model->save()) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->family_id;
                                $approval->apptype_id = EmployeeFamily::APP_TYPE;
                                $approval->comment = 'Pengajuan penambahan atau perubahan data Keluarga';
                                $approval->app_status = Logic::APP_STATUS_SUBMIT;
                                $approval->created_by = $person_id;
                                $approval->created_time = date("Y-m-d H:i:s");

                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
										return Logic::_sendResponse(200, 'Success', 'Pengajuan sedang dalam proses verifikasi oleh SDM');
                                    }
                                } else {
                                    $transaction->rollback();
									
									$errors = $approval->errors;
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
                        } else {
                            $transaction->rollback();
							
                            $errors = $model->upload()['errors'];
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
	
	// public function actionLoadinnovation() {
    //     if (Yii::$app->request->isAjax) {
    //         try {
    //             $person_id = Yii::$app->session->get('person_id');
               
    //             return $this->renderPartial('innovation/_innovation');
    //         } catch (\Exception $e) {
	// 			return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
    //         }
    //     }
    // }
    public function actionLoadinnovation() {
        if (Yii::$app->request->isAjax) {
            try {
                $person_id = Yii::$app->session->get('person_id');
                $model = new EmployeeInnovation;
				
                return $this->renderPartial('innovation/_innovation', ['model' => $model]);
            } catch (\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }

    public function actionLoaddtinnovation() {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$person_id = Yii::$app->session->get('person_id');
            $start = $_POST['start'];
            $length = $_POST['length'];
            $column = $_POST['columns'];

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
			
			$datawhere = EmployeeInnovation::find();
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
                  
					if ($value->app_status != 'ON PROGRESS') {
						$data['data'][$index][] = '<button onclick="showforminnovation(' . $value->innov_id . ', \'Edit\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i></button><button onclick="showforminnovation(' . $value->innov_id . ', \'Hapus\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill  btn-danger"><i class="icofont icofont-trash"></i></button>';
					}else{
						$data['data'][$index][] = '-';
					}
					
                    $data['data'][$index][] = '<button onclick="showpengajuan(' . $value->innov_id . ', \'innovation\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary">'.strip_tags($value->app_status).'</button>';
                    $data['data'][$index][] = $value->scope_name;
                    
                    $data['data'][$index][] = strip_tags($value->name);
                    $data['data'][$index][] = strip_tags($value->description);
                    $data['data'][$index][] = Logic::getIndoDate($value->date_of_innovation);
            
                    $data['data'][$index][] = file_exists(\Yii::getAlias('@webroot').$value->url_scan_document) && $value->url_scan_document ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $value->url_scan_document]) . '"><i class="icofont icofont-file-document"></i></a>' : '';
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
    public function actionShowforminnovation() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
				$person_id = Yii::$app->session->get('person_id');
				
                $model = EmployeeInnovation::findOne($dataid);
                if (empty($model)) $model = new EmployeeInnovation();
				
                $listscopeinnovation = MMaster::find()->andWhere(['key'=>'scope', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->employee->company_id], ['parent_id'=>Yii::$app->user->identity->employee->company_id]])->all();	
				
                return $this->renderPartial('innovation/_forminnovation', [
					'dataid' => $dataid,
					'dataaction' => $dataaction,
					'model' => $model,
					'listscopeinnovation'=>$listscopeinnovation
                ]);
            } catch (\Exception $e) {
                return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }

    public function actionCrudinnovation() {
        if (Yii::$app->request->isAjax) {
            try {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $person_id = Yii::$app->session->get('person_id');
                $dataid = $_POST['dataid'] == '' ? NULL : $_POST['dataid'];
                $dataaction = $_POST['dataaction'];
                $connection = Yii::$app->db;

                if ($dataaction == 'Hapus') {
                    $transaction = $connection->beginTransaction();
                    try {
                        $model = EmployeeInnovation::findOne($dataid);
                        if (!empty($model)) {
                            $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                            if ($model->save(false)) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->employee->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->innov_id;
                                $approval->apptype_id = EmployeeInnovation::APP_TYPE;
                                $approval->comment = 'Pengajuan penghapusan data Innovation';
                                $approval->created_by = $person_id;
                                $approval->app_status = Logic::APP_STATUS_REQUEST_DELETE;
                                $approval->created_time = date("Y-m-d H:i:s");
                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
                                        $data['code'] = 200;
                                        $data['status'] = 'Success';
                                        $data['message'] = 'Pengajuan sedang dalam proses verifikasi oleh SDM';
                                    }
                                } else {
                                    $errors = $approval->errors;
                                    $data['code'] = 200;
                                    $data['status'] = 'Failed';
                                    $data['message'] = '<ol>';
                                    foreach ($errors as $edx => $erow) {
                                        $data['message'] .= '<li>' . $erow[0] . '</li>';
                                    }
                                    $data['message'] .= '</ol>';
                                }
                            }
                        } else {
                            $data['code'] = 404;
                            $data['status'] = 'Failed';
                            $data['message'] = 'Data tidak ditemukan';
                        }
                    } catch (\Exception $e) {
                        $transaction->rollback();
                        $data['code'] = 404;
                        $data['status'] = 'Failed';
                        $data['message'] = Logic::Exceptionjson('Terjadi kesalahan! Silahkan hubungi administrator');
                    }
                } else if ($dataaction == 'Edit' || $dataaction == 'Tambah') {
                    $transaction = $connection->beginTransaction();
                    try {
						$post = Yii::$app->request->post();
                        $model = EmployeeInnovation::findOne($dataid);
                        if (empty($model)) {
							$model = new EmployeeInnovation();
							$model->load($post);
							$model->created_by = $person_id;
							$model->created_time = date('Y-m-d H:i:s');
						}else{
							$old_file = $model->url_scan_document;
							$model->load($post);
							$model->updated_by = $person_id;
							$model->updated_date = date('Y-m-d H:i:s');
						}
                        $model->date_of_innovation = $model->date_of_innovation;
						$model->company_id = Yii::$app->user->identity->employee->company_id;
                        $model->company_code = $model->company->code;
                        $model->company_name = $model->company->name;
                        //var_dump($model->company_name,$model->company_code,$model->company_id); exit;
                        $model->company_description = $model->company->description;
                        $model->person_id = $person_id;
                        $model->employee_id = $model->person->employee_id;
                        $model->person_name = $model->person->person_name;
						$model->scope_code = $model->scope->code;
                        $model->scope_name = $model->scope->name;
                        $model->scope_description = $model->scope->description;
                        $model->data_file = UploadedFile::getInstance($model, 'url_scan_document');
                        $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                        if ($model->upload($old_file, $person_id)['data'] == true) {
                            if ($model->save()) {
                                $approval = new Approval();
								$approval->company_id = Yii::$app->user->identity->employee->company_id;
                                $approval->person_id_sender = $person_id;
                                $approval->data_id = $model->innov_id;
                                $approval->apptype_id = EmployeeInnovation::APP_TYPE;
                                $approval->comment = 'Pengajuan penambahan atau perubahan data';
                                $approval->app_status = Logic::APP_STATUS_SUBMIT;
                                $approval->created_by = $person_id;
                                $approval->created_time = date("Y-m-d H:i:s");

                                if ($approval->validate()) {
                                    if ($approval->save()) {
                                        $transaction->commit();
										return Logic::_sendResponse(200, 'Success', 'Pengajuan sedang dalam proses verifikasi oleh SDM');
                                    }
                                } else {
                                    $transaction->rollback();
									
									$errors = $approval->errors;
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
                        } else {
                            $transaction->rollback();
							
                            $errors = $model->upload()['errors'];
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

	public function actionLoadskill() {
        if (Yii::$app->request->isAjax) {
            try {
                $person_id = Yii::$app->session->get('person_id');
               
                return $this->renderPartial('skill/_skill');
            } catch (\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }
	
	public function actionLoadreward() {
        if (Yii::$app->request->isAjax) {
            try {
                $person_id = Yii::$app->session->get('person_id');
               
                return $this->renderPartial('reward/_reward');
            } catch (\Exception $e) {
				return Logic::_sendResponse(500, 'Failed', $e->getMessage(), null, 'application/html');
            }
        }
    }

    public function actionShowformgn() {
        if (Yii::$app->request->isAjax) {
            try {
                $dataid = $_POST['dataid'] == '' ? NULL : strip_tags($_POST['dataid']);
                $dataaction = strip_tags($_POST['dataaction']);
                
                $model = Employee::findOne($dataid);
                if(empty($model)) $model = new Employee();

                return $this->renderPartial('general/_formgeneral', [
                    'dataid' => $dataid,
                    'dataaction' => $dataaction,
                    'model' => $model
                ]);
            } catch (\Exception $e) {
                return Logic::Exception($e->getMessage());
            }
        }
    }

    public function actionUpdategeneral(){
        try {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();

            if(Yii::$app->request->isAjax){
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $dataid = $_POST['dataid'];
                $post = Yii::$app->request->post();

                $model = EmployeeGeneral::find()->where(['person_id'=>$dataid])->one();
                
                if(empty($model)){
                    $model = new EmployeeGeneral;
                    $model->load($post);

                    $model->employee_id = Yii::$app->user->identity->employee->employee_id;
                    $model->person_id = Yii::$app->user->identity->employee->person_id;
                    $model->person_name = Yii::$app->user->identity->employee->person_name;
                    $model->sex = $post['Employee']['sex'];

                    $model->company_id = Yii::$app->user->identity->employee->company_id;
                    $model->company_code = Yii::$app->user->identity->employee->company_code;
                    $model->company_name = Yii::$app->user->identity->employee->company_name;
                    $model->company_description = Yii::$app->user->identity->employee->company_description;

                    $ethnic = MMaster::findOne($post['Employee']['ethnic_id']);
                    $model->ethnic_id = $ethnic->master_id;
                    $model->ethnic_code = $ethnic->code;
                    $model->ethnic_name = $ethnic->name;
                    $model->ethnic_description = $ethnic->description;

                    $religion = MMaster::findOne($post['Employee']['religion_id']);
                    $model->religion_id = $religion->master_id;
                    $model->religion_code = $religion->code;
                    $model->religion_name = $religion->name;
                    $model->religion_description = $religion->description;
                    
                    $marital = MMaster::findOne($post['Employee']['marital_id']);
                    $model->marital_id = $marital->master_id;
                    $model->marital_code = $marital->code;
                    $model->marital_name = $marital->name;
                    $model->marital_description = $marital->description;
                    
                    $town = MMaster::findOne($post['Employee']['town_of_birth_id']);
                    $model->town_of_birth_id = $town->master_id;
                    $model->town_of_birth_code = $town->code;
                    $model->town_of_birth_name = $town->name;
                    $model->town_of_birth_description = $town->description;
                    
                    $model->date_of_birth = $post['Employee']['date_of_birth'];
                    $model->app_status = Logic::APP_STATUS_SUBMIT;
                    $model->created_by = Yii::$app->user->identity->employee->person_id;
                    $model->created_time = date('Y-m-d H:i:s');

                }else{
                    $old_file = $model->url_photo;

                    $model->load($post);
                    $model->employee_id = Yii::$app->user->identity->employee->employee_id;
                    $model->person_id = Yii::$app->user->identity->employee->person_id;
                    $model->person_name = Yii::$app->user->identity->employee->person_name;
                    $model->sex = $post['Employee']['sex'];

                    $model->company_id = Yii::$app->user->identity->employee->company_id;
                    $model->company_code = Yii::$app->user->identity->employee->company_code;
                    $model->company_name = Yii::$app->user->identity->employee->company_name;
                    $model->company_description = Yii::$app->user->identity->employee->company_description;

                    $ethnic = MMaster::findOne($post['Employee']['ethnic_id']);
                    $model->ethnic_id = $ethnic->master_id;
                    $model->ethnic_code = $ethnic->code;
                    $model->ethnic_name = $ethnic->name;
                    $model->ethnic_description = $ethnic->description;

                    $religion = MMaster::findOne($post['Employee']['religion_id']);
                    $model->religion_id = $religion->master_id;
                    $model->religion_code = $religion->code;
                    $model->religion_name = $religion->name;
                    $model->religion_description = $religion->description;
                    
                    $marital = MMaster::findOne($post['Employee']['marital_id']);
                    $model->marital_id = $marital->master_id;
                    $model->marital_code = $marital->code;
                    $model->marital_name = $marital->name;
                    $model->marital_description = $marital->description;
                    
                    $town = MMaster::findOne($post['Employee']['town_of_birth_id']);
                    $model->town_of_birth_id = $town->master_id;
                    $model->town_of_birth_code = $town->code;
                    $model->town_of_birth_name = $town->name;
                    $model->town_of_birth_description = $town->description;
                    
                    $model->date_of_birth = $post['Employee']['date_of_birth'];
                    $model->app_status = Logic::APP_STATUS_ON_PROGRESS;
                    $model->updated_by = Yii::$app->user->identity->employee->person_id;
                    $model->updated_time = date('Y-m-d H:i:s');

                }

                $model->data_file = UploadedFile::getInstanceByName('Employee[url_photo]');


                if($model->validate()){
                    if($model->upload($old_file, Yii::$app->user->identity->person_id)['data'] == true){
                        $model->save();

                        $approval = new Approval();
                        $approval->company_id = Yii::$app->user->identity->employee->company_id;
                        $approval->person_id_sender = $model->person_id;
                        $approval->data_id = $model->persongen_id;
                        $approval->apptype_id = EmployeeGeneral::APP_TYPE;
                        $approval->comment = 'Pengajuan penambahan atau perubahan data Informasi Diri';
                        $approval->app_status = Logic::APP_STATUS_SUBMIT;
                        $approval->created_by = $model->person_id;
                        $approval->created_time = date("Y-m-d H:i:s");

                        if ($approval->validate()) {
                            if ($approval->save()) {
                                $transaction->commit();
                                return Logic::_sendResponse(200, 'Success', 'Pengajuan sedang dalam proses verifikasi oleh SDM');
                            }
                        } else {
                            $transaction->rollback();
                            
                            $errors = $approval->errors;
                            $message = '<ol>';
                            foreach ($errors as $edx => $erow) {
                                $message .= '<li>' . $erow[0] . '</li>';
                            }
                            $message .= '</ol>';
                            return Logic::_sendResponse(200, 'Failed', $message);
                        }
                    }
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
        } catch (\Exception $e) {
            return Logic::Exception($e->getMessage());
        }
        
    } 

    

    

    public function actionPrintpdf($person_id){
        //die('test');
		$model = Employee::findOne($person_id);
        $kontak = EmployeeContact::find()->where(['person_id'=>$person_id])->all();
        $no_contact = EmployeeContact::find()->where(['person_id'=>$person_id])->one();
        $alamat = EmployeeAddress::find()->where(['person_id'=>$person_id])->one();
        $identitas = EmployeeIdentity::find()->where(['person_id'=>$person_id])->one();
        $pendidikan = EmployeeEducation::find()->where(['person_id'=>$person_id])->all();
        $pelatihan = EmployeeTraining::find()->where(['person_id'=>$person_id])->all();
        $keluarga = EmployeeFamily::find()->where(['person_id'=>$person_id])->all();
        //var_dump($pelatihan);exit;
		$slugify = new Slugify();
		$file_name = $slugify->slugify($model->person_name.' CV');
		
		$content = $this->renderPartial('cv/_preview', ['model'=>$model, 'kontak'=>$kontak, 'alamat' =>$alamat, 'pendidikan' =>$pendidikan, 'pelatihan'=>$pelatihan, 'identitas'=>$identitas, 'keluarga'=>$keluarga, 'no_contact' => $no_contact]);
		
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'format' => Pdf::FORMAT_A4,
			'orientation' => Pdf::ORIENT_PORTRAIT, 
			'destination' => Pdf::DEST_BROWSER, 
			'content' => $content,
			'cssFile' => \Yii::getAlias('@webroot').'/themes/cuba/assets/css/pdf/bootstrap.css',
			'cssInline' => '', 
			'options' => ['title' => $model->person_name.' CV'],
			'methods' => [ 
				'SetHeader'=>['PT. CYBERS BLITZ NUSANTARA'], 
				'SetFooter'=>['{PAGENO}'],
				'SetTitle' => $model->person_name.' CV'
			],
			'filename' => $file_name.'.pdf',
		]);
		
		return $pdf->render(); 
	}	
}
