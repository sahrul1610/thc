<?php

namespace app\models;

use Yii;
use app\components\Logic;

/**
 * This is the model class for table "employee_identity".
 *
 * @property int $identity_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property int $identitytype_id
 * @property string $identitytype_code
 * @property string $identitytype_name
 * @property string|null $identitytype_description
 * @property string $no_identity
 * @property string|null $url_scan_identity
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeIdentity extends \yii\db\ActiveRecord
{
	const APP_TYPE = 141;
	
	public $data_file;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_identity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'person_id', 'employee_id', 'person_name', 'identitytype_id', 'identitytype_code', 'identitytype_name', 'no_identity', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'identitytype_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'identitytype_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'identitytype_description', 'url_scan_identity'], 'string'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
			[['data_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf' , 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2],
            [['company_code', 'identitytype_code'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'identitytype_name'], 'string', 'max' => 255],
            [['employee_id', 'no_identity'], 'string', 'max' => 50],
            [['app_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identity_id' => 'Identity ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'Employee ID',
            'person_name' => 'Person Name',
            'identitytype_id' => 'JENIS IDENTITAS',
            'identitytype_code' => 'Identitytype Code',
            'identitytype_name' => 'Identitytype Name',
            'identitytype_description' => 'Identitytype Description',
            'no_identity' => 'NO IDENTITAS',
            'url_scan_identity' => 'DOKUMEN IDENTITAS (Maks 2MB : png, jpg, pdf)',
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
					'identitytype_id' => 'JENIS IDENTITAS',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'no_identity' => 'NO IDENTITAS',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'url_scan_identity' => 'DOKUMEN IDENTITAS',
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
					'identitytype_id' => 'JENIS IDENTITAS',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'no_identity' => 'NO IDENTITAS',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'url_scan_identity' => 'DOKUMEN IDENTITAS',
				],
				'class'=>'nofilter'
			]
		];
	}
	
	// public function customAttributeLabelsMonitoringExcel()
    // {
    //     return [
    //         'no' => 'NO',
    //         'person_id' => 'KARYAWAN',
    //         'identitytype_id' => 'JENIS IDENTITAS',
    //         'no_identity' => 'NO IDENTITAS'
    //     ];
    // }

	public function customAttributeLabels2()
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
					'app_status' => 'STATUS PENGAJUAN',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'employee_id' => 'NIK',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'person_name' => 'NAMA',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'identitytype_id' => 'JENIS IDENTITAS',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'no_identity' => 'NO IDENTITAS',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'url_scan_identity' => 'DOKUMEN IDENTITAS',
				],
				'class'=>'nofilter'
			]
		];
	}

	public function customAttributeLabelsMonitoringExcel()
    {
        return [
            'no' => 'NO',
            'app_status' => 'STATUS PENGAJUAN',
            'employee_id' => 'NIK',
            'person_name' => 'NAMA',
            //'person_id' => 'KARYAWAN',
            'identitytype_id' => 'JENIS IDENTITAS',
            'no_identity' => 'NO IDENTITAS'
        ];
    }

    /**
     * {@inheritdoc}
     * @return EmployeeIdentityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeIdentityQuery(get_called_class());
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
					$pathroot = $pathcompany.'/identitas';
					$createroot = mkdir($pathroot);
					if($createroot == true){
						$modroot = chmod($pathroot, 0777);
					}
				}else{
					$createcompany = mkdir($pathcompany);
                    if($createcompany == true){
                        $modcompany = chmod($pathcompany, 0777);
                        if($modcompany == true){
							$pathroot = $pathcompany.'/identitas';
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
				
				$pathabsolute = '/storage/company/'.Logic::slugify($this->company->name).'/identitas/'.$this->person->employee_id;
				
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
	
	public function getIdentitytype() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'identitytype_id']);
    }
}
