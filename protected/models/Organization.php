<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property int $org_id
 * @property string $org_code
 * @property string $org_abbr
 * @property string|null $org_parent
 * @property string $org_name
 * @property bool|null $is_chief
 * @property int $psa_id
 * @property string $psa_code
 * @property string $psa_name
 * @property string|null $psa_description
 * @property string $unit_code
 * @property string $unit_name
 * @property int $band_id
 * @property string $band_code
 * @property string $band_name
 * @property string|null $band_description
 * @property int $jobposition_id
 * @property string $jobposition_code
 * @property string $jobposition_name
 * @property string|null $jobposition_description
 * @property int $jobfunction_id
 * @property string $jobfunction_code
 * @property string $jobfunction_name
 * @property string|null $jobfunction_description
 * @property bool $is_active
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 * @property int $company_id
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_code', 'org_abbr', 'org_name', 'psa_id', 'psa_code', 'psa_name', 'unit_code', 'unit_name', 'band_id', 'band_code', 'band_name', 'jobposition_id', 'jobposition_code', 'jobposition_name', 'jobfunction_id', 'jobfunction_code', 'jobfunction_name', 'created_by', 'created_time', 'company_id'], 'required'],
            [['is_chief', 'is_active'], 'boolean'],
            [['psa_id', 'band_id', 'jobposition_id', 'jobfunction_id', 'created_by', 'updated_by', 'deleted_by', 'company_id'], 'default', 'value' => null],
            [['psa_id', 'band_id', 'jobposition_id', 'jobfunction_id', 'created_by', 'updated_by', 'deleted_by', 'company_id'], 'integer'],
            [['psa_description', 'band_description', 'jobposition_description', 'jobfunction_description'], 'string'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['org_code'], 'string', 'max' => 30],
            [['org_abbr', 'org_parent'], 'string', 'max' => 20],
            [['org_name', 'psa_name', 'unit_name', 'band_name', 'jobposition_name', 'jobfunction_name'], 'string', 'max' => 255],
            [['psa_code', 'unit_code', 'band_code', 'jobposition_code', 'jobfunction_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'org_id' => 'ORG',
            'company_id' => 'COMPANY',
            'company_code' => 'KODE PERUSAHAAN',
            'company_name' => 'NAMA PERUSAHAAN',
            'company_description' => 'DESKRIPSI PERUSAHAAN',
            'org_code' => 'OBJIDPOSISI',
            'org_abbr' => 'KODE POSISI',
            'org_parent' => 'KODE UNIT ATASAN',
            'org_name' => 'NAMA POSISI',
            'is_chief' => 'IS CHIEF ?',
            'psa_id' => 'PSA',
            'psa_code' => 'KODE PSA',
            'psa_name' => 'NAMA PSA',
            'psa_description' => 'DESKRIPSI PSA',
            'unit_code' => 'KODE UNIT',
            'unit_name' => 'NAMA UNIT',
            'band_id' => 'BAND',
            'band_code' => 'KODE BAND',
            'band_name' => 'NAMA BAND',
            'band_description' => 'DESKRIPSI BAND',
            'jobposition_id' => 'JOB POSISI',
            'jobposition_code' => 'KODE JOB POSISI',
            'jobposition_name' => 'NAMA JOB POSISI',
            'jobposition_description' => 'DESKRIPSI JOB POSISI',
            'jobfunction_id' => 'JOB FUNCTION',
            'jobfunction_code' => 'KODE JOB FUNCTION',
            'jobfunction_name' => 'NAMA JOB FUNCTION',
            'jobfunction_description' => 'DESKRIPSI JOB FUNCTION',
            'is_active' => 'STATUS',
            'created_by' => 'CREATED BY',
            'created_time' => 'CREATED TIME',
            'updated_by' => 'UPDATED BY',
            'updated_time' => 'UPDATED TIME',
            'deleted_by' => 'DELETED BY',
            'deleted_time' => 'DELETED TIME',
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
					'org_parent' => 'KODE UNIT ATASAN',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'is_chief' => 'IS CHIEF ?',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'unit_code' => 'KODE UNIT',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'unit_name' => 'NAMA UNIT',
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

	public function getCreated() {
        return $this->hasOne(Employee::className(), ['person_id' => 'created_by']);
    }
	
	public function getUpdated() {
        return $this->hasOne(Employee::className(), ['person_id' => 'updated_by']);
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
	
    /**
     * {@inheritdoc}
     * @return OrganizationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrganizationQuery(get_called_class());
    }
}
