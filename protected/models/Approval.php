<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use app\components\Logic;
/**
 * This is the model class for table "approval".
 *
 * @property int $approval_id
 * @property int $company_id
 * @property int $person_id_sender
 * @property int|null $person_id_approval
 * @property int $data_id
 * @property int $apptype_id
 * @property string $comment
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 */
class Approval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'approval';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'person_id_sender', 'data_id', 'apptype_id', 'comment', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id_sender', 'person_id_approval', 'data_id', 'apptype_id', 'created_by'], 'default', 'value' => null],
            [['company_id', 'person_id_sender', 'person_id_approval', 'data_id', 'apptype_id', 'created_by'], 'integer'],
            [['comment','justification'], 'string'],
            [['created_time'], 'safe'],
            [['app_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'approval_id' => 'Approval ID',
            'company_id' => 'Company ID',
            'person_id_sender' => 'PENGAJU',
            'person_id_approval' => 'Person Id Approval',
            'data_id' => 'Data ID',
            'apptype_id' => 'TIPE PENGAJUAN',
            'comment' => 'Comment',
            'justification' => 'Justification',
            'app_status' => 'STATUS PENGAJUAN',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ApprovalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApprovalQuery(get_called_class());
    }
	
	public function getApprovaltype() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'apptype_id']);
    }
	
	public function getPersonpengaju() {
        return $this->hasOne(Employee::className(), ['person_id' => 'person_id_sender']);
    }
	
	public function getPersonpemroses() {
        return $this->hasOne(Employee::className(), ['person_id' => 'person_id_approval']);
    }
	
	public function getDatapengajuan($is_multiple = null) {
		if($is_multiple){
			if($this->apptype_id == 133){
				$model = EmployeeTraining::findOne($this->data_id);
			
				$data['card_body']['header'][] = $this->getAttributeLabel('apptype_id'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('person_id_sender'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('app_status'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('title'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('institute'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('location'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('tgl_range_pelatihan'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('no_certification'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('url_scan_certification'); 
				$data['card_body']['body'][] = $this->approvaltype->name; 
				$data['card_body']['body'][] = $this->personpengaju->employee_id.' / '.$this->personpengaju->person_name; 
				$data['card_body']['body'][] = $model->app_status; 
				$data['card_body']['body'][] = $model->title; 
				$data['card_body']['body'][] = $model->institute; 
				$data['card_body']['body'][] = $model->location; 
				$data['card_body']['body'][] = Logic::getIndoDate($model->start_of_training).' s/d '.Logic::getIndoDate($model->end_of_training); 
				$data['card_body']['body'][] = $model->no_certification; 
				$data['card_body']['body'][] = file_exists(\Yii::getAlias('@webroot').$model->url_scan_certification) && $model->url_scan_certification ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_certification]) . '"><i class="icofont icofont-file-document"></i></a>' : '-'; 
			}else if($this->apptype_id == 134){
				$model = EmployeeEducation::findOne($this->data_id);
				
				$data['card_body']['header'][] = $this->getAttributeLabel('apptype_id'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('person_id_sender'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('app_status'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('level_id'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('institute'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('major'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('year_of_study'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('year_of_passed'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('no_certification'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('url_scan_certification'); 
				$data['card_body']['body'][] = $this->approvaltype->name; 
				$data['card_body']['body'][] = $this->personpengaju->employee_id.' / '.$this->personpengaju->person_name; 
				$data['card_body']['body'][] = $model->app_status; 
				$data['card_body']['body'][] = $model->level_code; 
				$data['card_body']['body'][] = $model->institute; 
				$data['card_body']['body'][] = $model->major; 
				$data['card_body']['body'][] = $model->year_of_study; 
				$data['card_body']['body'][] = $model->year_of_passed; 
				$data['card_body']['body'][] = $model->no_identity; 
				$data['card_body']['body'][] = file_exists(\Yii::getAlias('@webroot').$model->url_scan_identity) && $model->url_scan_identity ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_identity]) . '"><i class="icofont icofont-file-document"></i></a>' : '-'; 
			}else if($this->apptype_id == 84037){
				$model = EmployeeInnovation::findOne($this->data_id);
				
				$data['card_body']['header'][] = $this->getAttributeLabel('apptype_id'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('person_id_sender'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('app_status'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('scope_id'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('name'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('description'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('date_of_innovation');  
				$data['card_body']['header'][] = $model->getAttributeLabel('url_scan_document'); 
				$data['card_body']['body'][] = $this->approvaltype->name; 
				$data['card_body']['body'][] = $this->personpengaju->employee_id.' / '.$this->personpengaju->person_name; 
				$data['card_body']['body'][] = $model->app_status; 
				$data['card_body']['body'][] = $model->scope_name; 
				$data['card_body']['body'][] = $model->name; 
				$data['card_body']['body'][] = $model->description; 
				$data['card_body']['body'][] = $model->date_of_innovation; 
				$data['card_body']['body'][] = file_exists(\Yii::getAlias('@webroot').$model->url_scan_document) && $model->url_scan_document ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_document]) . '"><i class="icofont icofont-file-document"></i></a>' : '-'; 
			}else if($this->apptype_id == 135){
				$model = EmployeeFamily::findOne($this->data_id);
				$sex = $model->sex == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN';
				
				$data['card_body']['header'][] = $this->getAttributeLabel('apptype_id'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('person_id_sender'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('app_status'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('famtype_id'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('famstatus_id'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('name'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('sex'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('date_of_birth'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('no_marital'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('url_scan_marital'); 
				$data['card_body']['body'][] = $this->approvaltype->name; 
				$data['card_body']['body'][] = $this->personpengaju->employee_id.' / '.$this->personpengaju->person_name; 
				$data['card_body']['body'][] = $model->famtype_name; 
				$data['card_body']['body'][] = $model->famtype_id; 
				$data['card_body']['body'][] = $model->famstatus_name; 
				$data['card_body']['body'][] = $model->name; 
				$data['card_body']['body'][] = $sex; 
				$data['card_body']['body'][] = Logic::getIndoDate($model->date_of_birth); 
				$data['card_body']['body'][] = $model->no_marital; 
				$data['card_body']['body'][] = file_exists(\Yii::getAlias('@webroot').$model->url_scan_marital) && $model->url_scan_marital ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_marital]) . '"><i class="icofont icofont-file-document"></i></a>' : '-'; 
			}else if($this->apptype_id == 141){
				$model = EmployeeIdentity::findOne($this->data_id);
				
				$data['card_body']['header'][] = $this->getAttributeLabel('apptype_id'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('person_id_sender'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('app_status'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('identitytype_id');
				$data['card_body']['header'][] = $model->getAttributeLabel('no_identity'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('url_scan_identity'); 
				$data['card_body']['body'][] = $this->approvaltype->name; 
				$data['card_body']['body'][] = $this->personpengaju->employee_id.' / '.$this->personpengaju->person_name; 
				$data['card_body']['body'][] = $model->app_status; 
				$data['card_body']['body'][] = $model->identitytype_name; 
				$data['card_body']['body'][] = $model->no_identity; 
				$data['card_body']['body'][] = file_exists(\Yii::getAlias('@webroot').$model->url_scan_identity) && $model->url_scan_identity ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_identity]) . '"><i class="icofont icofont-file-document"></i></a>' : '-'; 
			}else if($this->apptype_id == 136){
				$model = EmployeeContact::findOne($this->data_id);
				
				$data['card_body']['header'][] = $this->getAttributeLabel('apptype_id'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('person_id_sender'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('app_status'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('contacttype_id');
				$data['card_body']['header'][] = $model->getAttributeLabel('no_identity'); 
				$data['card_body']['body'][] = $this->approvaltype->name; 
				$data['card_body']['body'][] = $this->personpengaju->employee_id.' / '.$this->personpengaju->person_name; 
				$data['card_body']['body'][] = $model->app_status; 
				$data['card_body']['body'][] = $model->contacttype_name; 
				$data['card_body']['body'][] = $model->no_contact; 
			}else if($this->apptype_id == 137){
				$model = EmployeeAddress::findOne($this->data_id);
				
				$data['card_body']['header'][] = $this->getAttributeLabel('apptype_id'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('person_id_sender'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('app_status'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('location_id');
				$data['card_body']['header'][] = $model->getAttributeLabel('address'); 
				$data['card_body']['body'][] = $this->approvaltype->name; 
				$data['card_body']['body'][] = $this->personpengaju->employee_id.' / '.$this->personpengaju->person_name; 
				$data['card_body']['body'][] = $model->app_status; 
				$data['card_body']['body'][] = $model->location_name; 
				$data['card_body']['body'][] = $model->address; 
			}else if($this->apptype_id == 142){
				$model = EmployeeGeneral::findOne($this->data_id);

				$data['card_body']['header'][] = $this->getAttributeLabel('apptype_id'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('person_id_sender'); 
				$data['card_body']['header'][] = $this->getAttributeLabel('app_status'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('sex');
				$data['card_body']['header'][] = $model->getAttributeLabel('ethnic_name'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('religion_name'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('town_of_birth_name'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('date_of_birth'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('marital_name'); 
				$data['card_body']['header'][] = $model->getAttributeLabel('url_photo'); 
				$data['card_body']['body'][] = $this->approvaltype->name; 
				$data['card_body']['body'][] = $this->personpengaju->employee_id.' / '.$this->personpengaju->person_name; 
				$data['card_body']['body'][] = $model->app_status; 
				$data['card_body']['body'][] = $model->sex == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN';
				$data['card_body']['body'][] = $model->ethnic_name;
				$data['card_body']['body'][] = $model->religion_name;
				$data['card_body']['body'][] = $model->town_of_birth_name;
				$data['card_body']['body'][] = $model->date_of_birth;
				$data['card_body']['body'][] = $model->marital_name;
				$data['card_body']['body'][] = file_exists(\Yii::getAlias('@webroot').$model->url_photo) && $model->url_photo ? '<a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_photo]) . '"><i class="icofont icofont-file-document"></i></a>' : '-'; 
			}
		}else{
			if($this->apptype_id == 133){
				$model = EmployeeTraining::findOne($this->data_id);
				$data['status'] = $model->app_status; 
				$data['hasil'] = ''; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('title').' : '.$model->title.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('institute').' : '.$model->institute.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('location').' : '.$model->location.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('tgl_range_pelatihan').' : '.Logic::getIndoDate($model->start_of_training).' s/d '.Logic::getIndoDate($model->end_of_training).'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('no_certification').' : '.$model->no_certification.'</div>'; 
				if(file_exists(\Yii::getAlias('@webroot').$model->url_scan_certification)  && $model->url_scan_certification){
					$data['hasil'] .= '<div>'.$model->getAttributeLabel('url_scan_certification').' : <a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_certification]) . '"><i class="icofont icofont-file-document"></i></a></div>'; 
				}
			}else if($this->apptype_id == 134){
				$model = EmployeeEducation::findOne($this->data_id);
				$data['status'] = $model->app_status; 
				$data['hasil'] = ''; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('level_id').' : '.$model->level_code.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('institute').' : '.$model->institute.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('major').' : '.$model->major.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('year_of_study').' : '.$model->year_of_study.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('year_of_passed').' : '.$model->year_of_passed.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('no_identity').' : '.$model->no_identity.'</div>'; 
				if(file_exists(\Yii::getAlias('@webroot').$model->url_scan_identity)  && $model->url_scan_identity){
					$data['hasil'] .= '<div>'.$model->getAttributeLabel('url_scan_identity').' : <a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_identity]) . '"><i class="icofont icofont-file-document"></i></a></div>'; 
				}
			}else if($this->apptype_id == 84037){
				$model = EmployeeInnovation::findOne($this->data_id);
				$data['status'] = $model->app_status; 
				$data['hasil'] = ''; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('scope_id').' : '.$model->scope_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('name').' : '.$model->name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('description').' : '.$model->description.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('date_of_innovation').' : '.$model->date_of_innovation.'</div>'; 
				if(file_exists(\Yii::getAlias('@webroot').$model->url_scan_document)  && $model->url_scan_document){
					$data['hasil'] .= '<div>'.$model->getAttributeLabel('url_scan_document').' : <a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_document]) . '"><i class="icofont icofont-file-document"></i></a></div>'; 
				}
			}else if($this->apptype_id == 135){
				$model = EmployeeFamily::findOne($this->data_id);
				$sex = $model->sex == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN';
				$data['status'] = $model->app_status; 
				$data['hasil'] = ''; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('famtype_id').' : '.$model->famtype_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('famstatus_id').' : '.$model->famstatus_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('name').' : '.$model->name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('sex').' : '.$sex.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('date_of_birth').' : '.Logic::getIndoDate($model->date_of_birth).'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('no_marital').' : '.$model->no_marital.'</div>'; 
				if(file_exists(\Yii::getAlias('@webroot').$model->url_scan_marital)  && $model->url_scan_marital){
					$data['hasil'] .= '<div>'.$model->getAttributeLabel('url_scan_marital').' : <a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_marital]) . '"><i class="icofont icofont-file-document"></i></a></div>'; 
				}
			}else if($this->apptype_id == 141){
				$model = EmployeeIdentity::findOne($this->data_id);
				$data['status'] = $model->app_status; 
				$data['hasil'] = ''; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('identitytype_id').' : '.$model->identitytype_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('no_identity').' : '.$model->no_identity.'</div>'; 
				if(file_exists(\Yii::getAlias('@webroot').$model->url_scan_identity)  && $model->url_scan_identity){
					$data['hasil'] .= '<div>'.$model->getAttributeLabel('url_scan_identity').' : <a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_identity]) . '"><i class="icofont icofont-file-document"></i></a></div>'; 
				}
			}else if($this->apptype_id == 136){
				$model = EmployeeContact::findOne($this->data_id);
				$data['status'] = $model->app_status; 
				$data['hasil'] = ''; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('contacttype_id').' : '.$model->contacttype_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('no_contact').' : '.$model->no_contact.'</div>'; 
			}else if($this->apptype_id == 137){
				$model = EmployeeAddress::findOne($this->data_id);
				$data['status'] = $model->app_status; 
				$data['hasil'] = ''; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('location_id').' : '.$model->location_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('address').' : '.$model->address.'</div>'; 
			}else if($this->apptype_id == 142){
				$model = EmployeeGeneral::findOne($this->data_id);
				$sex = $model->sex == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN';
				$data['status'] = $model->app_status; 
				$data['hasil'] = '';
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('employee_id').' : '.$model->employee_id.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('person_name').' : '.$model->person_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('sex').' : '.$sex.'</div>';
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('ethnic_name').' : '.$model->ethnic_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('religion_name').' : '.$model->religion_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('town_of_birth_name').' : '.$model->town_of_birth_name.'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('date_of_birth').' : '.Logic::getIndoDate($model->date_of_birth).'</div>'; 
				$data['hasil'] .= '<div>'.$model->getAttributeLabel('marital_name').' : '.$model->marital_name.'</div>'; 
				if(file_exists(\Yii::getAlias('@webroot').$model->url_photo)  && $model->url_photo){
					$data['hasil'] .= '<div>'.$model->getAttributeLabel('url_photo').' : <a title="Download File" href="' . Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_photo]) . '"><i class="icofont icofont-file-document"></i></a></div>'; 
				}
			}
		}
		return $data;
    }
}
