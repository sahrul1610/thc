<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_reward".
 *
 * @property int $reward_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property string $name
 * @property string|null $description
 * @property string $date_of_reward
 * @property string|null $url_scan_document
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeReward extends \yii\db\ActiveRecord
{
	const APP_TYPE = 84037;
	
	public $data_file;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_reward';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'person_id', 'employee_id', 'person_name', 'name', 'date_of_reward', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'description'], 'string'],
            [['date_of_reward', 'created_time', 'updated_time', 'deleted_time'], 'safe'],
			[['data_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf' , 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2],
            [['company_code'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'name', 'url_scan_document'], 'string', 'max' => 255],
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
            'reward_id' => 'Reward ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'NIK KARYAWAN',
            'person_name' => 'NAMA KARYAWAN',
            'name' => 'NAMA PENGHARGAAN',
            'description' => 'DESKRIPSI PENGHARGAAN',
            'date_of_reward' => 'TGL PENGHARGAAN',
            'url_scan_document' => 'DOKUMEN PENGHARGAAN',
            'app_status' => 'STATUS PENGAJUAN',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
            'deleted_by' => 'Deleted By',
            'deleted_time' => 'Deleted Time',
        ];
    }

    /**
     * {@inheritdoc}
     * @return EmployeeRewardQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeRewardQuery(get_called_class());
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
					$pathroot = $pathcompany.'/penghargaan';
					$createroot = mkdir($pathroot);
					if($createroot == true){
						$modroot = chmod($pathroot, 0777);
					}
				}else{
					$createcompany = mkdir($pathcompany);
                    if($createcompany == true){
                        $modcompany = chmod($pathcompany, 0777);
                        if($modcompany == true){
							$pathroot = $pathcompany.'/penghargaan';
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
				
				$pathabsolute = '/storage/company/'.Logic::slugify($this->company->name).'/penghargaan/'.$this->person->employee_id;
				
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
}
