<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_document".
 *
 * @property int $document_id
 * @property int $company_id
 * @property string $company_code
 * @property string $company_name
 * @property string|null $company_description
 * @property int $project_id
 * @property string $project_code
 * @property string $project_name
 * @property string|null $project_description
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string $url_scan_document
 * @property bool $is_active
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 */
class ProjectDocument extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_code', 'company_name', 'project_id', 'project_code', 'project_name', 'code', 'name', 'url_scan_document', 'created_by', 'created_time'], 'required'],
            [['company_id', 'project_id', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['company_id', 'project_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['company_description', 'project_description', 'description'], 'string'],
            [['is_active'], 'boolean'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
            [['company_code', 'project_code', 'code'], 'string', 'max' => 100],
            [['company_name', 'project_name', 'name', 'url_scan_document'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'document_id' => 'Document ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'company_description' => 'Company Description',
            'project_id' => 'Project ID',
            'project_code' => 'Project Code',
            'project_name' => 'Project Name',
            'project_description' => 'Project Description',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'url_scan_document' => 'Url Scan Document',
            'is_active' => 'Is Active',
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
     * @return ProjectDocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectDocumentQuery(get_called_class());
    }
}
