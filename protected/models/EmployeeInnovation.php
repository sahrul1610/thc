<?php

namespace app\models;

use Yii;
use app\components\Logic;

/**
 * This is the model class for table "employee_innovation".
 *
 * @property int $innov_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property int $scope_id
 * @property string $scope_code
 * @property string $scope_name
 * @property string|null $scope_description
 * @property string $name
 * @property string|null $description
 * @property string $date_of_innovation
 * @property string|null $url_scan_document
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeInnovation extends \yii\db\ActiveRecord
{
	const APP_TYPE = 84034;
	
	public $data_file;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_innovation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'person_id', 'employee_id', 'person_name', 'scope_id', 'scope_code', 'scope_name', 'name', 'date_of_innovation', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'scope_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'scope_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'scope_description', 'description'], 'string'],
            [['date_of_innovation', 'created_time', 'updated_time', 'deleted_time'], 'safe'],
			[['data_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf' , 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2],
            [['company_code', 'scope_code'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'scope_name', 'name', 'url_scan_document'], 'string', 'max' => 255],
            [['employee_id'], 'string', 'max' => 50],
            [['app_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'innov_id' => 'Innov ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'NIK KARYAWAN',
            'person_name' => 'NAMA KARYAWAN',
            'scope_id' => 'RUANG LINGKUP INOVASI',
            'scope_code' => 'Scope Code',
            'scope_name' => 'Scope Name',
            'scope_description' => 'Scope Description',
            'name' => 'NAMA INOVASI',
            'description' => 'DESKRIPSI INOVASI',
            'date_of_innovation' => 'TGL INOVASI',
            'url_scan_document' => 'DOKUMEN INOVASI (Maks 2MB : png, jpg, pdf)',
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
					'scope_id' => 'RUANG LINGKUP INOVASI',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'name' => 'NAMA INOVASI',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'description' => 'DESKRIPSI INOVASI',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'date_of_innovation' => 'TGL INOVASI',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'url_scan_document' => 'DOKUMEN INOVASI',
				],
				'class'=>'nofilter'
			]
		];
	}
    /**
     * {@inheritdoc}
     * @return EmployeeInnovationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeInnovationQuery(get_called_class());
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
					$pathroot = $pathcompany.'/inovasi';
					$createroot = mkdir($pathroot);
					if($createroot == true){
						$modroot = chmod($pathroot, 0777);
					}
				}else{
					$createcompany = mkdir($pathcompany);
                    if($createcompany == true){
                        $modcompany = chmod($pathcompany, 0777);
                        if($modcompany == true){
							$pathroot = $pathcompany.'/inovasi';
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
				
				$pathabsolute = '/storage/company/'.Logic::slugify($this->company->name).'/inovasi/'.$this->person->employee_id;
				
				$file_name = Logic::slugify($this->data_file->baseName);
				
				$this->data_file->saveAs($pathperson.'/'.$file_name.'.'. $this->data_file->extension);
				$this->url_scan_document = $pathabsolute.'/'.$file_name.'.'. $this->data_file->extension;
			}else{
				$this->url_scan_document = $old_file;
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
	
	public function getScope() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'scope_id']);
    }
}
