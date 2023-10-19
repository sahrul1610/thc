<?php

namespace app\models;

use Yii;
use app\components\Logic;

/**
 * This is the model class for table "employee_training".
 *
 * @property int $td_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property int $trg_id
 * @property string $trg_code
 * @property string $trg_name
 * @property string|null $trg_description
 * @property string $title
 * @property string $institute
 * @property string|null $no_certification
 * @property string|null $url_scan_certification
 * @property string $location
 * @property string|null $start_of_training
 * @property string|null $end_of_training
 * @property float|null $cost
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeTraining extends \yii\db\ActiveRecord
{
	const APP_TYPE = 133;
	
	public $tgl_range_pelatihan, $data_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_training';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'start_of_training', 'end_of_training', 'person_id', 'employee_id', 'person_name', 'trg_id', 'trg_code', 'trg_name', 'title', 'institute', 'location', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'trg_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'trg_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'trg_description'], 'string'],
            [['start_of_training', 'end_of_training', 'created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['cost'], 'number'],
			[['data_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf' , 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2],
            [['company_code', 'trg_code', 'title', 'institute', 'location'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'trg_name', 'url_scan_certification'], 'string', 'max' => 255],
            [['employee_id', 'no_certification'], 'string', 'max' => 50],
            [['app_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'td_id' => 'Td ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'Employee ID',
            'person_name' => 'Person Name',
            'trg_id' => 'JENIS PELATIHAN',
            'trg_code' => 'KODE PELATIHAN',
            'trg_name' => 'NAMA PELATIHAN',
            'trg_description' => 'Trg Description',
            'title' => 'NAMA PELATIHAN',
            'institute' => 'INSTITUSI PELATIHAN',
            'no_certification' => 'NO SERTIFIKAT PELATIHAN',
            'url_scan_certification' => 'DOKUMEN PELATIHAN (Maks 2MB : png, jpg, pdf)',
            'location' => 'LOKASI PELATIHAN',
            'start_of_training' => 'TGL MULAI PELATIHAN',
            'end_of_training' => 'TGL SELESAI PELATIHAN',
            'tgl_range_pelatihan' => 'TGL PELATIHAN',
            'cost' => 'BIAYA PELATIHAN',
            'app_status' => 'STATUS PENGAJUAN',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
            'deleted_by' => 'Deleted By',
            'deleted_time' => 'Deleted Time',
        ];
    }
	
	public function customAttributeLabels()
    {
        return [
			[
				'name'=> [
					'no' => 'NO',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'action' => 'ACTION',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'app_status' => 'STATUS PENGAJUAN',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'start_of_training' => 'TGL MULAI PELATIHAN',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'end_of_training' => 'TGL SELESAI PELATIHAN',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'trg_id' => 'JENIS PELATIHAN',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'location' => 'LOKASI PELATIHAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'title' => 'NAMA PELATIHAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'institute' => 'INSTITUSI PELATIHAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'no_certification' => 'NO SERTIFIKAT PELATIHAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'url_scan_certification' => 'DOKUMEN PELATIHAN',
				],
				'class'=>'nofilter'
			]
		];
	}

    /**
     * {@inheritdoc}
     * @return EmployeeTrainingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeTrainingQuery(get_called_class());
    }
	
	public function upload($old_file = null, $person_id = null)
    {
        if ($this->validate()) {
			if(!empty($this->data_file)){
				if(!empty($old_file)){
					unlink(\Yii::getAlias('@webroot').$old_file);
				}
				$pathcompany = \Yii::getAlias('@webroot/storage/company').'/'.Logic::slugify($this->company->name);
				if(file_exists($pathcompany)){
					$pathroot = $pathcompany.'/pelatihan';
					$createroot = mkdir($pathroot);
					if($createroot == true){
						$modroot = chmod($pathroot, 0777);
					}
				}else{
					$createcompany = mkdir($pathcompany);
                    if($createcompany == true){
                        $modcompany = chmod($pathcompany, 0777);
                        if($modcompany == true){
							$pathroot = $pathcompany.'/pelatihan';
							$createroot = mkdir($pathroot);
							if($createroot == true){
								$modroot = chmod($pathroot, 0777);
							}
						}
					}
				}
				
				$pathperson = $pathroot.'/'.$this->person->employee_id;
				if(!file_exists($pathperson)){
					$createperson = mkdir($pathperson);
					if($createperson == true){
						$modperson = chmod($pathperson, 0777);
					}
				}	
				
				$pathabsolute = '/storage/company/'.Logic::slugify($this->company->name).'/pelatihan/'.$this->person->employee_id;
				
				$file_name = Logic::slugify($this->data_file->baseName);
				
				$this->data_file->saveAs($pathperson.'/'.$file_name.'.'. $this->data_file->extension);
				$this->url_scan_certification = $pathabsolute.'/'.$file_name.'.'. $this->data_file->extension;
			}else{
				$this->url_scan_certification = $old_file;
			}
            return ['data'=>true];
        } else {
            return [
				'data'=>false,
				'errors'=>$this->errors
			];
        }
    }
	
	public function getCreated() {
        return $this->hasOne(Employee::className(), ['person_id' => 'created_by']);
    }
	
	public function getUpdated() {
        return $this->hasOne(Employee::className(), ['person_id' => 'updated_by']);
    }
	
	public function getCompany() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'company_id']);
    }
	
	public function getPerson() {
        return $this->hasOne(Employee::className(), ['person_id' => 'person_id']);
    }
	
	public function getTraininggroup() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'trg_id']);
    }
}
