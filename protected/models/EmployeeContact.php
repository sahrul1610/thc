<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_contact".
 *
 * @property int $contact_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property int $contacttype_id
 * @property string $contacttype_code
 * @property string $contacttype_name
 * @property string|null $contacttype_description
 * @property string $no_contact
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class EmployeeContact extends \yii\db\ActiveRecord
{
	const APP_TYPE = 136;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'person_id', 'employee_id', 'person_name', 'contacttype_id', 'contacttype_code', 'contacttype_name', 'no_contact', 'created_by', 'created_time'], 'required'],
            [['company_id', 'person_id', 'contacttype_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'person_id', 'contacttype_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'contacttype_description'], 'string'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['company_code', 'contacttype_code'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'contacttype_name'], 'string', 'max' => 255],
            [['employee_id', 'no_contact'], 'string', 'max' => 50],
            [['app_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contact_id' => 'Contact ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_id' => 'Person ID',
            'employee_id' => 'Employee ID',
            'person_name' => 'Person Name',
            'contacttype_id' => 'JENIS KONTAK',
            'contacttype_code' => 'Contacttype Code',
            'contacttype_name' => 'Contacttype Name',
            'contacttype_description' => 'Contacttype Description',
            'no_contact' => 'NO KONTAK',
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
					'contacttype_id' => 'JENIS KONTAK',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'no_contact' => 'NO KONTAK',
				],
				'class'=>'freetext'
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
					'contacttype_id' => 'JENIS KONTAK',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'no_contact' => 'NO KONTAK',
				],
				'class'=>'freetext'
			]
		];
	}
	
	public function customAttributeLabelsMonitoringExcel()
    {
        return [
            'no' => 'NO',
            'person_id' => 'KARYAWAN',
            'contacttype_id' => 'JENIS KONTAK',
            'no_contact' => 'NO KONTAK'
        ];
    }
	
    /**
     * {@inheritdoc}
     * @return EmployeeContactQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeContactQuery(get_called_class());
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
	
	public function getContacttype() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'contacttype_id']);
    }
}
