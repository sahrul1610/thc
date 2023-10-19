<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $person_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property string $person_name
 * @property string $employee_id
 * @property string $sex
 * @property int|null $org_id
 * @property string|null $org_code
 * @property string|null $org_name
 * @property string|null $org_parent
 * @property string|null $org_abbr
 * @property bool|null $org_is_chief
 * @property int|null $empgroup_id
 * @property string|null $empgroup_code
 * @property string|null $empgroup_name
 * @property string|null $empgroup_description
 * @property int|null $empsubgroup_id
 * @property string|null $empsubgroup_code
 * @property string|null $empsubgroup_name
 * @property string|null $empsubgroup_description
 * @property int|null $band_id
 * @property string|null $band_code
 * @property string|null $band_name
 * @property string|null $band_description
 * @property int|null $psa_id
 * @property string|null $psa_code
 * @property string|null $psa_name
 * @property string|null $psa_description
 * @property int|null $jobposition_id
 * @property string|null $jobposition_code
 * @property string|null $jobposition_name
 * @property string|null $jobposition_description
 * @property int|null $jobfunction_id
 * @property string|null $jobfunction_code
 * @property string|null $jobfunction_name
 * @property string|null $jobfunction_description
 * @property int|null $ethnic_id
 * @property string|null $ethnic_code
 * @property string|null $ethnic_name
 * @property string|null $ethnic_description
 * @property int|null $religion_id
 * @property string|null $religion_code
 * @property string|null $religion_name
 * @property string|null $religion_description
 * @property int|null $payroll_id
 * @property string|null $payroll_code
 * @property string|null $payroll_name
 * @property string|null $payroll_description
 * @property int|null $marital_id
 * @property string|null $marital_code
 * @property string|null $marital_name
 * @property string|null $marital_description
 * @property int|null $town_of_birth_id
 * @property string|null $town_of_birth_code
 * @property string|null $town_of_birth_name
 * @property string|null $town_of_birth_description
 * @property string|null $date_of_birth
 * @property string|null $date_of_hire
 * @property string|null $date_of_work
 * @property string|null $date_of_retire
 * @property string|null $date_of_kdmp
 * @property string|null $date_of_emp_sub_group
 * @property string|null $date_of_position
 * @property string|null $date_of_band_position
 * @property string|null $date_of_adjusted
 * @property string|null $date_of_dedicated
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'person_name', 'employee_id', 'sex', 'created_by', 'created_time'], 'required'],
            [['company_id', 'org_id', 'empgroup_id', 'empsubgroup_id', 'band_id', 'psa_id', 'jobposition_id', 'jobfunction_id', 'ethnic_id', 'religion_id', 'payroll_id', 'marital_id', 'town_of_birth_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'org_id', 'empgroup_id', 'empsubgroup_id', 'band_id', 'psa_id', 'jobposition_id', 'jobfunction_id', 'ethnic_id', 'religion_id', 'payroll_id', 'marital_id', 'town_of_birth_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'empgroup_description', 'empsubgroup_description', 'band_description', 'psa_description', 'jobposition_description', 'jobfunction_description', 'ethnic_description', 'religion_description', 'payroll_description', 'marital_description', 'town_of_birth_description'], 'string'],
            [['org_is_chief'], 'boolean'],
            [['date_of_birth', 'date_of_hire', 'date_of_work', 'date_of_retire', 'date_of_kdmp', 'date_of_emp_sub_group', 'date_of_position', 'date_of_band_position', 'date_of_adjusted', 'date_of_dedicated', 'created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['company_code', 'empgroup_code', 'empsubgroup_code', 'band_code', 'psa_code', 'jobposition_code', 'jobfunction_code', 'ethnic_code', 'religion_code', 'payroll_code', 'marital_code', 'town_of_birth_code', 'org_unit_code'], 'string', 'max' => 100],
            [['company_name', 'person_name', 'org_name', 'empgroup_name', 'empsubgroup_name', 'band_name', 'psa_name', 'jobposition_name', 'jobfunction_name', 'ethnic_name', 'religion_name', 'payroll_name', 'marital_name', 'town_of_birth_name', 'org_unit_name'], 'string', 'max' => 255],
            [['employee_id'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 1],
            [['org_code'], 'string', 'max' => 30],
            [['org_parent', 'org_abbr'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'person_id' => 'Person ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'person_name' => 'NAMA KARYAWAN',
            'employee_id' => 'NIK KARYAWAN',
            'sex' => 'JENIS KELAMIN',
            'org_id' => 'OBJIDPOSISI',
            'org_code' => 'OBJIDPOSISI',
            'org_name' => 'NAMA POSISI',
            'org_parent' => 'NAMA UNIT ATASAN',
            'org_abbr' => 'KODE POSISI',
            'org_is_chief' => 'Org Is Chief',
            'org_unit_code' => 'KODE UNIT',
            'org_unit_name' => 'NAMA UNIT',
            'empgroup_id' => 'GRUP KARYAWAN',
            'empgroup_code' => 'KODE GRUP KARYAWAN',
            'empgroup_name' => 'NAMA GRUP KARYAWAN',
            'empgroup_description' => 'Empgroup Description',
            'empsubgroup_id' => 'SUB GRUP KARYAWAN',
            'empsubgroup_code' => 'KODE SUB GRUP KARYAWAN',
            'empsubgroup_name' => 'NAMA SUB GRUP KARYAWAN',
            'empsubgroup_description' => 'Empsubgroup Description',
            'band_id' => 'BAND',
            'band_code' => 'KODE BAND',
            'band_name' => 'NAMA BAND',
            'band_description' => 'Band Description',
            'psa_id' => 'PSA',
            'psa_code' => 'KODE PSA',
            'psa_name' => 'NAMA PSA',
            'psa_description' => 'Psa Description',
            'jobposition_id' => 'JOB POSISI',
            'jobposition_code' => 'KODE JOB POSISI',
            'jobposition_name' => 'NAMA JOB POSISI',
            'jobposition_description' => 'Jobposition Description',
            'jobfunction_id' => 'JOB FUNCTION',
            'jobfunction_code' => 'KODE JOB FUNCTION',
            'jobfunction_name' => 'NAMA JOB FUNCTION',
            'jobfunction_description' => 'Jobfunction Description',
            'ethnic_id' => 'SUKU',
            'ethnic_code' => 'KODE SUKU',
            'ethnic_name' => 'NAMA SUKU',
            'ethnic_description' => 'Ethnic Description',
            'religion_id' => 'AGAMA',
            'religion_code' => 'KODE AGAMA',
            'religion_name' => 'NAMA AGAMA',
            'religion_description' => 'Religion Description',
            'payroll_id' => 'PAYROLL AREA',
            'payroll_code' => 'KODE PAYROLL AREA',
            'payroll_name' => 'NAMA PAYROLL AREA',
            'payroll_description' => 'Payroll Description',
            'marital_id' => 'MARITAL',
            'marital_code' => 'KODE MARITAL',
            'marital_name' => 'STATUS MARITAL',
            'marital_description' => 'Marital Description',
            'town_of_birth_id' => 'TEMPAT LAHIR',
            'town_of_birth_code' => 'KODE TEMPAT LAHIR',
            'town_of_birth_name' => 'NAMA TEMPAT LAHIR',
            'town_of_birth_description' => 'Town Of Birth Description',
            'date_of_birth' => 'TGL LAHIR',
            'date_of_hire' => 'TGL REKRUT',
            'date_of_work' => 'TGL BEKERJA (RIIL)',
            'date_of_retire' => 'TGL BERHENTI',
            'date_of_kdmp' => 'TGL KDMP',
            'date_of_emp_sub_group' => 'Date Of Emp Sub Group',
            'date_of_position' => 'TGL POSISI',
            'date_of_band_position' => 'TGL BAND POSISI',
            'date_of_adjusted' => 'TGL BEKERJA (ADJUSTED)',
            'date_of_dedicated' => 'TGL DEDICATED',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
            'deleted_by' => 'Deleted By',
            'deleted_time' => 'Deleted Time',
            'url_photo' => 'FOTO (Maks 2MB : png, jpg, webp)',
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
					'psa_code' => 'KODE PERUSAHAAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'psa_name' => 'NAMA PERUSAHAAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'employee_id' => 'NIK KARYAWAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'person_name' => 'NAMA KARYAWAN',
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
					'org_code' => 'OBJIDPOSISI',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'org_abbr' => 'KODE POSISI',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'org_name' => 'NAMA POSISI',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'org_parent' => 'KODE UNIT ATASAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'org_is_chief' => 'IS CHIEF ?',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'org_unit_code' => 'KODE UNIT',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'org_unit_name' => 'NAMA UNIT',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'empgroup_code' => 'KODE GRUP KARYAWAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'empgroup_name' => 'NAMA GRUP KARYAWAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'empsubgroup_code' => 'KODE SUB GRUP KARYAWAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'empsubgroup_name' => 'NAMA SUB GRUP KARYAWAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'band_code' => 'KODE BAND',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'band_name' => 'NAMA BAND',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'psa_code' => 'KODE PSA',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'psa_name' => 'NAMA PSA',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'jobposition_code' => 'KODE JOB POSISI',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'jobposition_name' => 'NAMA JOB POSISI',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'jobfunction_code' => 'KODE JOB FUNCTION',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'jobfunction_name' => 'NAMA JOB FUNCTION',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'ethnic_code' => 'KODE SUKU',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'ethnic_name' => 'NAMA SUKU',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'religion_code' => 'KODE AGAMA',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'religion_name' => 'NAMA AGAMA',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'payroll_code' => 'KODE PAYROLL AREA',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'payroll_name' => 'NAMA PAYROLL AREA',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'marital_code' => 'KODE MARITAL',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'marital_name' => 'STATUS  MARITAL',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'town_of_birth_code' => 'KODE TEMPAT LAHIR',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'town_of_birth_name' => 'NAMA TEMPAT LAHIR',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'date_of_birth' => 'TGL LAHIR',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_hire' => 'TGL REKRUT',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_work' => 'TGL BEKERJA (RIIL)',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_retire' => 'TGL BERHENTI',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_kdmp' => 'TGL KDMP',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_position' => 'TGL POSISI',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_nand_position' => 'TGL BAND POSISI',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_adjusted' => 'TGL BEKERJA (ADJUSTED)',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'date_of_dedicated' => 'TGL DEDICATED',
				],
				'class'=>'date'
			],
			[
				'name'=> [
					'created_by' => 'CREATED BY',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'created_time' => 'CREATED TIME',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'updated_by' => 'UPDATED BY',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'updated_time' => 'UPDATED TIME',
				],
				'class'=>'nofilter'
			]
		];
	}

	public function customAttributeLabels2(){
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
					'employee_id' => 'NIK KARYAWAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'person_name' => 'NAMA KARYAWAN',
				],
				'class'=>'freetext'
			],
		];
		
	}
	
	public function getCreated() {
        return $this->hasOne(Employee::className(), ['person_id' => 'created_by']);
    }
	
	public function getUpdated() {
        return $this->hasOne(Employee::className(), ['person_id' => 'updated_by']);
    }
	
	public function getOrg() {
        return $this->hasOne(Organization::className(), ['org_id' => 'org_id']);
    }
	
	public function getEmpgroup() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'empgroup_id']);
    }
	
	public function getEmpsubgroup() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'empsubgroup_id']);
    }
	
	public function getCompany() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'company_id']);
    }
	
	public function getPsa() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'psa_id']);
    }
	
	public function getBand() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'band_id']);
    }
	
	public function getJobposition() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'jobposition_id']);
    }
	
	public function getJobfunction() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'jobfunction_id']);
    }
	
	public function getEthnic() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'ethnic_id']);
    }
	
	public function getReligion() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'religion_id']);
    }
	
	public function getPayroll() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'payroll_id']);
    }
	
	public function getMarital() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'marital_id']);
    }
	
	public function getArea() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'town_of_birth_id']);
    }

    public function getEmployeegeneral() {
        return $this->hasOne(EmployeeGeneral::className(), ['person_id' => 'person_id']);
    }
	
    /**
     * {@inheritdoc}
     * @return EmployeeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeQuery(get_called_class());
    }
}
