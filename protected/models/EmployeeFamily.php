<?php

namespace app\models;

use Yii;
use app\components\Logic;

/**
 * This is the model class for table "employee_family".
 *
 * @property int $family_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int|null $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property int $famtype_id
 * @property string $famtype_code
 * @property string $famtype_name
 * @property string|null $famtype_description
 * @property int $famstatus_id
 * @property string $famstatus_code
 * @property string $famstatus_name
 * @property string|null $famstatus_description
 * @property int $profession_id
 * @property string $profession_code
 * @property string $profession_name
 * @property string|null $profession_description
 * @property int $town_of_birth_id
 * @property string $town_of_birth_code
 * @property string $town_of_birth_name
 * @property string|null $town_of_birth_description
 * @property bool $is_still_alive
 * @property bool|null $is_dependent
 * @property string|null $date_of_birth
 * @property string|null $date_of_marital
 * @property string|null $date_of_divorce
 * @property string|null $date_of_dead
 * @property string|null $no_marital
 * @property string|null $url_scan_marital
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeFamily extends \yii\db\ActiveRecord
{
	const APP_TYPE = 135;
	
	public $data_file;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_family';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'employee_id', 'person_name', 'name', 'sex', 'famtype_id', 'famtype_code', 'famtype_name', 'famstatus_id', 'famstatus_code', 'famstatus_name', 'profession_id', 'profession_code', 'profession_name', 'town_of_birth_id', 'town_of_birth_code', 'town_of_birth_name', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'famtype_id', 'famstatus_id', 'profession_id', 'town_of_birth_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'famtype_id', 'famstatus_id', 'profession_id', 'town_of_birth_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'famtype_description', 'famstatus_description', 'profession_description', 'town_of_birth_description'], 'string'],
            [['is_still_alive', 'is_dependent'], 'boolean'],
            [['date_of_birth', 'date_of_marital', 'date_of_divorce', 'date_of_dead', 'created_time', 'updated_time', 'deleted_time'], 'safe'],
			[['data_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf' , 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2],
            [['company_code', 'famtype_code', 'famstatus_code', 'profession_code', 'town_of_birth_code'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'famtype_name', 'famstatus_name', 'profession_name', 'town_of_birth_name', 'url_scan_marital'], 'string', 'max' => 255],
            [['employee_id', 'no_marital'], 'string', 'max' => 50],
            [['app_status'], 'string', 'max' => 15],
            [['sex'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'family_id' => 'Family ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'Employee ID',
            'person_name' => 'Person Name',
            'name' => 'NAMA KELUARGA',
            'sex' => 'JENIS KELAMIN',
            'famtype_id' => 'TIPE KELUARGA',
            'famtype_code' => 'KODE TIPE KELUARGA',
            'famtype_name' => 'NAMA TIPE KELUARGA',
            'famtype_description' => 'Famtype Description',
            'famstatus_id' => 'STATUS KELUARGA',
            'famstatus_code' => 'KODE STATUS KELUARGA',
            'famstatus_name' => 'NAMA STATUS KELUARGA',
            'famstatus_description' => 'Famstatus Description',
            'profession_id' => 'PROFESI KELUARGA',
            'profession_code' => 'KODE PROFESI KELUARGA',
            'profession_name' => 'NAMA PROFESI KELUARGA',
            'profession_description' => 'Profession Description',
            'town_of_birth_id' => 'TEMPAT LAHIR',
            'town_of_birth_code' => 'KODE TEMPAT LAHIR',
            'town_of_birth_name' => 'NAMA TEMPAT LAHIR',
            'town_of_birth_description' => 'Town Of Birth Description',
            'is_still_alive' => 'MASIH HIDUP ?',
            'is_dependent' => 'MASIH TANGGUNGAN ?',
            'date_of_birth' => 'TGL LAHIR',
            'date_of_marital' => 'TGL MENIKAH',
            'date_of_divorce' => 'TGL BERCERAI',
            'date_of_dead' => 'TGL MENINGGAL',
            'no_marital' => 'NO MARITAL',
            'url_scan_marital' => 'DOKUMEN MARITAL (Maks 2MB : png, jpg, pdf)',
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
					'famtype_id' => 'TIPE KELUARGA',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'famstatus_id' => 'STATUS KELUARGA',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'name' => 'NAMA',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'sex' => 'JENIS KELAMIN',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'date_of_birth' => 'TGL LAHIR',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'town_of_birth_id' => 'TEMPAT LAHIR',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'profession_id' => 'PROFESI KELUARGA',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'is_still_alive' => 'MASIH HIDUP ?',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'is_dependent' => 'MASIH TANGGUNGAN ?',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'date_of_marital' => 'TGL MENIKAH',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_divorce' => 'TGL BERCERAI',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_dead' => 'TGL MENINGGAL',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'no_marital' => 'NO MARITAL',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'url_scan_marital' => 'DOKUMEN MARITAL',
				],
				'class'=>'nofilter'
			],
		];
	}
    /**
     * {@inheritdoc}
     * @return EmployeeFamilyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeFamilyQuery(get_called_class());
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
					$pathroot = $pathcompany.'/keluarga';
					$createroot = mkdir($pathroot);
					if($createroot == true){
						$modroot = chmod($pathroot, 0777);
					}
				}else{
					$createcompany = mkdir($pathcompany);
                    if($createcompany == true){
                        $modcompany = chmod($pathcompany, 0777);
                        if($modcompany == true){
							$pathroot = $pathcompany.'/keluarga';
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
				
				$pathabsolute = '/storage/company/'.Logic::slugify($this->company->name).'/keluarga/'.$this->person->employee_id;
				
				$file_name = Logic::slugify($this->data_file->baseName);
				
				$this->data_file->saveAs($pathperson.'/'.$file_name.'.'. $this->data_file->extension);
				$this->url_scan_marital = $pathabsolute.'/'.$file_name.'.'. $this->data_file->extension;
			}else{
				$this->url_scan_marital = $old_file;
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
	
	public function getFamilytype() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'famtype_id']);
    }
	
	public function getFamilystatus() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'famstatus_id']);
    }
	
	public function getProfession() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'profession_id']);
    }
	
	public function getTown() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'town_of_birth_id']);
    }
}
