<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_address".
 *
 * @property int $address_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property int $location_id
 * @property string $location_code
 * @property string $location_name
 * @property string|null $location_description
 * @property string $address
 * @property string $app_status
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeAddress extends \yii\db\ActiveRecord
{
	const APP_TYPE = 137;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'person_id', 'employee_id', 'person_name', 'location_id', 'address', 'app_status', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'location_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'location_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'location_description', 'address'], 'string'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['company_code', 'location_code'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'location_name'], 'string', 'max' => 255],
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
            'address_id' => 'Address ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'Employee ID',
            'person_name' => 'Person Name',
            'location_id' => 'PROVINSI/KABUPATEN/KOTA/KECAMATAN/KELURAHAN/DESA',
            'location_code' => 'Location Code',
            'location_name' => 'Location Name',
            'location_description' => 'Location Description',
            'address' => 'ALAMAT LENGKAP',
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
					'location_id' => 'PROVINSI/KABUPATEN/KOTA/KECAMATAN/KELURAHAN/DESA',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'address' => 'ALAMAT LENGKAP',
				],
				'class'=>'freetext'
			]
		];
	}

    /**
     * {@inheritdoc}
     * @return EmployeeAddressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeAddressQuery(get_called_class());
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
	
	public function getLocation() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'location_id']);
    }
}
