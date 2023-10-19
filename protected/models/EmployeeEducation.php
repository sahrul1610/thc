<?php

namespace app\models;

use Yii;
use app\components\Logic;
/**
 * This is the model class for table "employee_education".
 *
 * @property int $edu_id
 * @property int $company_id
 * @property string $company_code
 * @property string|null $company_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property int $level_id
 * @property string $level_code
 * @property string $level_name
 * @property string|null $level_description
 * @property string $institute
 * @property string|null $major
 * @property string $year_of_study
 * @property string|null $year_of_passed
 * @property string|null $no_identity
 * @property string|null $url_scan_identity
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeEducation extends \yii\db\ActiveRecord
{
	const APP_TYPE = 134;
	
	public $data_file;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_education';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'person_id', 'employee_id', 'person_name', 'level_id', 'level_code', 'level_name', 'institute', 'year_of_study', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'level_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'level_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'level_description'], 'string'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['company_code', 'company_name', 'level_code', 'institute', 'major'], 'string', 'max' => 100],
            [['employee_id', 'no_identity'], 'string', 'max' => 50],
			[['data_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf' , 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2],
            [['person_name', 'level_name', 'url_scan_identity'], 'string', 'max' => 255],
            [['year_of_study', 'year_of_passed'], 'string', 'max' => 4],
            [['app_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'edu_id' => 'Edu ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'Employee ID',
            'person_name' => 'Person Name',
            'level_id' => 'LEVEL PENDIDIKAN',
            'level_code' => 'KODE LEVEL PENDIDIKAN',
            'level_name' => 'NAMA LEVEL PENDIDIKAN',
            'level_description' => 'Level Description',
            'institute' => 'INSTITUSI PENDIDIKAN',
            'major' => 'JURUSAN PENDIDIKAN',
            'year_of_study' => 'TAHUN MASUK PENDIDIKAN',
            'year_of_passed' => 'TAHUN LULUS PENDIDIKAN',
            'no_identity' => 'NO IJAZAH PENDIDIKAN',
            'url_scan_identity' => 'DOKUMEN PENDIDIKAN (Maks 2MB : png, jpg, pdf)',
            'app_status' => 'App Status',
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
					'level_id' => 'LEVEL PENDIDIKAN',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'institute' => 'INSTITUSI PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'major' => 'JURUSAN PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'year_of_study' => 'TAHUN MASUK PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'year_of_passed' => 'TAHUN LULUS PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'no_identity' => 'NO IJAZAH PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'url_scan_identity' => 'DOKUMEN PENDIDIKAN',
				],
				'class'=>'nofilter'
			]
		];
	}
	
	public function customAttributeLabelsMonitoring()
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
					'person_id' => 'KARYAWAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'level_id' => 'LEVEL PENDIDIKAN',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'institute' => 'INSTITUSI PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'major' => 'JURUSAN PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'year_of_study' => 'TAHUN MASUK PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'year_of_passed' => 'TAHUN LULUS PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'no_identity' => 'NO IJAZAH PENDIDIKAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'url_scan_identity' => 'DOKUMEN PENDIDIKAN',
				],
				'class'=>'nofilter'
			]
		];
	}
	
	public function customAttributeLabelsMonitoringExcel()
    {
        return [
            'no' => 'NO',
            'person_id' => 'KARYAWAN',
            'level_id' => 'LEVEL PENDIDIKAN',
            'institute' => 'INSTITUSI PENDIDIKAN',
            'major' => 'JURUSAN PENDIDIKAN',
            'year_of_study' => 'TAHUN MASUK PENDIDIKAN',
            'year_of_passed' => 'TAHUN LULUS PENDIDIKAN',
            'no_identity' => 'NO IJAZAH PENDIDIKAN'
        ];
    }

    /**
     * {@inheritdoc}
     * @return EmployeeEducationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeEducationQuery(get_called_class());
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
					$pathroot = $pathcompany.'/pendidikan';
					$createroot = mkdir($pathroot);
					if($createroot == true){
						$modroot = chmod($pathroot, 0777);
					}
				}else{
					$createcompany = mkdir($pathcompany);
                    if($createcompany == true){
                        $modcompany = chmod($pathcompany, 0777);
                        if($modcompany == true){
							$pathroot = $pathcompany.'/pendidikan';
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
				
				$pathabsolute = '/storage/company/'.Logic::slugify($this->company->name).'/pendidikan/'.$this->person->employee_id;
				
				$file_name = Logic::slugify($this->data_file->baseName);
				
				$this->data_file->saveAs($pathperson.'/'.$file_name.'.'. $this->data_file->extension);
				$this->url_scan_identity = $pathabsolute.'/'.$file_name.'.'. $this->data_file->extension;
			}else{
				$this->url_scan_identity = $old_file;
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
	
	public function getEducationlevel() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'level_id']);
    }
}
