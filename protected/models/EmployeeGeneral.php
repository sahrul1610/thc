<?php

namespace app\models;

use Yii;
use app\components\Logic;

/**
 * This is the model class for table "employee_general".
 *
 * @property int $persongen_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property string $sex
 * @property int $ethnic_id
 * @property string $ethnic_code
 * @property string $ethnic_name
 * @property string|null $ethnic_description
 * @property int $religion_id
 * @property string $religion_code
 * @property string $religion_name
 * @property string|null $religion_description
 * @property int $town_of_birth_id
 * @property string $town_of_birth_code
 * @property string $town_of_birth_name
 * @property string|null $town_of_birth_description
 * @property string $date_of_birth
 * @property int $marital_id
 * @property string $marital_code
 * @property string $marital_name
 * @property string|null $marital_description
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeGeneral extends \yii\db\ActiveRecord
{
	const APP_TYPE = 142;
	
	public $data_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_general';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'person_id', 'employee_id', 'person_name', 'sex', 'ethnic_id', 'religion_id', 'town_of_birth_id', 'date_of_birth', 'marital_id', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'ethnic_id', 'religion_id', 'town_of_birth_id', 'marital_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'ethnic_id', 'religion_id', 'town_of_birth_id', 'marital_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'ethnic_description', 'religion_description', 'town_of_birth_description', 'marital_description'], 'string'],
            [['date_of_birth', 'created_time', 'updated_time', 'deleted_time'], 'safe'],
			[['data_file', 'url_photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, webp' , 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2],
            [['company_code', 'ethnic_code', 'religion_code', 'town_of_birth_code', 'marital_code'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'ethnic_name', 'religion_name', 'town_of_birth_name', 'marital_name'], 'string', 'max' => 255],
            [['employee_id'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 1],
            [['app_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'persongen_id' => 'Persongen ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'NIK',
            'person_name' => 'NAMA',
            'sex' => 'JENIS KELAMIN',
            'ethnic_id' => 'SUKU',
            'ethnic_code' => 'Ethnic Code',
            'ethnic_name' => 'SUKU',
            'ethnic_description' => 'Ethnic Description',
            'religion_id' => 'AGAMA',
            'religion_code' => 'Religion Code',
            'religion_name' => 'AGAMA',
            'religion_description' => 'Religion Description',
            'town_of_birth_id' => 'TEMPAT LAHIR',
            'town_of_birth_code' => 'Town Of Birth Code',
            'town_of_birth_name' => 'TEMPAT LAHIR',
            'town_of_birth_description' => 'Town Of Birth Description',
            'date_of_birth' => 'TANGGAL LAHIR',
            'marital_id' => 'MARITAL',
            'marital_code' => 'Marital Code',
            'marital_name' => 'STATUS MARITAL',
            'marital_description' => 'Marital Description',
            'app_status' => 'App Status',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
            'deleted_by' => 'Deleted By',
            'deleted_time' => 'Deleted Time',
            'url_photo' => 'FOTO (Maks 2MB : png, jpg, webp)',
        ];
    }

    /**
     * {@inheritdoc}
     * @return EmployeeGeneralQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeGeneralQuery(get_called_class());
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
					$pathroot = $pathcompany.'/foto';
					$createroot = mkdir($pathroot);
					if($createroot == true){
						$modroot = chmod($pathroot, 0777);
					}
				}else{
					$createcompany = mkdir($pathcompany);
                    if($createcompany == true){
                        $modcompany = chmod($pathcompany, 0777);
                        if($modcompany == true){
							$pathroot = $pathcompany.'/foto';
							$createroot = mkdir($pathroot);
							if($createroot == true){
								$modroot = chmod($pathroot, 0777);
							}
						}
					}
				}
				
				$pathabsolute = '/storage/company/'.Logic::slugify($this->company->name).'/foto';
				
				$file_name = Logic::slugify($this->person->person_name);
				
				$this->data_file->saveAs($pathroot.'/'.$file_name.'.'. $this->data_file->extension);
				$this->url_photo = $pathabsolute.'/'.$file_name.'.'. $this->data_file->extension;
			}else{
				$this->url_photo = $old_file;
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
	
	public function getEthnic() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'ethnic_id']);
    }
	
	public function getReligion() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'religion_id']);
    }
	
	public function getMarital() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'marital_id']);
    }
	
	public function getTown() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'town_of_birth_id']);
    }
}
