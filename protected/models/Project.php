<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $project_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $groupproject_id
 * @property string $groupproject_code
 * @property string $groupproject_name
 * @property string|null $groupproject_description
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $no_contract
 * @property string|null $client_company
 * @property string|null $client_unit
 * @property bool|null $is_done
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 * @property string|null $bast_1
 * @property string|null $bast_2
 * @property string|null $amendment
 */
class Project extends \yii\db\ActiveRecord
{
	public $project_member, $range_date, $pm_project, $technical_leader;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'groupproject_id', 'groupproject_code', 'groupproject_name', 'code', 'name', 'created_by', 'created_time'], 'required'],
            [['company_id', 'groupproject_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'groupproject_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'groupproject_description', 'description'], 'string'],
            [['start_date', 'end_date', 'created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['is_done'], 'boolean'],
            [['company_code', 'groupproject_code', 'code', 'client_company'], 'string', 'max' => 100],
            [['company_name', 'groupproject_name', 'name', 'client_unit'], 'string', 'max' => 255],
            [['no_contract', 'bast_1', 'bast_2', 'amendment'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'groupproject_id' => 'NAMA GRUP PROJECT',
            'groupproject_code' => 'Groupproject Code',
            'groupproject_name' => 'NAMA GRUP PROJECT',
            'groupproject_description' => 'Groupproject Description',
            'code' => 'KODE PROJECT',
            'name' => 'NAMA PROJECT',
            'description' => 'DESKRIPSI PROJECT',
            'start_date' => 'TGL MULAI PROJECT',
            'end_date' => 'TGL SELESAI PROJECT',
            'no_contract' => 'NO KONTRAK',
            'client_company' => 'PERUSAHAAN KLIEN',
            'client_unit' => 'UNIT KLIEN',
            'is_done' => 'Is Done',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
            'deleted_by' => 'Deleted By',
            'deleted_time' => 'Deleted Time',
            'bast_1' => 'NO BAST 1',
            'bast_2' => 'NO BAST 2',
            'amendment' => 'NO AMANDEMEN',
            'project_member' => 'PROJECT MEMBERS',
            'range_date' => 'MASA PROJECT',
            'pm_project' => 'PM PROJECT',
            'technical_leader' => 'TECHNICAL LEADER',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
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
	
	public function getGroupproject() {
        return $this->hasOne(MMaster::className(), ['master_id' => 'groupproject_id']);
    }
}
