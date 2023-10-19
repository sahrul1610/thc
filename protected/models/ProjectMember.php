<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_member".
 *
 * @property int $member_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $project_id
 * @property string $project_code
 * @property string $project_name
 * @property string|null $project_description
 * @property int $person_id
 * @property string $employee_id
 * @property string $person_name
 * @property bool|null $is_pm
 * @property string|null $pm_label
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class ProjectMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'project_id', 'project_code', 'project_name', 'person_id', 'employee_id', 'person_name', 'created_by', 'created_time'], 'required'],
            [['company_id', 'project_id', 'person_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'project_id', 'person_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'project_description'], 'string'],
            [['is_pm'], 'boolean'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['company_code', 'project_code'], 'string', 'max' => 100],
            [['company_name', 'project_name', 'person_name'], 'string', 'max' => 255],
            [['employee_id', 'pm_label'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'project_id' => 'Project ID',
            'project_code' => 'Project Code',
            'project_name' => 'Project Name',
            'project_description' => 'Project Description',
            'person_id' => 'Person ID',
            'employee_id' => 'Employee ID',
            'person_name' => 'Person Name',
            'is_pm' => 'Is Pm',
            'pm_label' => 'Pm Label',
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
     * @return ProjectMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectMemberQuery(get_called_class());
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
