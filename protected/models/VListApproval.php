<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_list_approval".
 *
 * @property int|null $approval_id
 * @property int|null $company_id
 * @property string|null $company_code
 * @property string|null $company_name
 * @property int|null $person_id_sender
 * @property string|null $employee_id_sender
 * @property string|null $person_name_sender
 * @property int|null $person_id_approval
 * @property string|null $employee_id_approval
 * @property string|null $person_name_approval
 * @property int|null $data_id
 * @property int|null $apptype_id
 * @property string|null $apptype_name
 * @property string|null $comment
 * @property string|null $justification
 * @property string|null $app_status
 * @property int|null $created_by
 * @property string|null $created_time
 */
class VListApproval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_list_approval';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['approval_id', 'company_id', 'person_id_sender', 'person_id_approval', 'data_id', 'apptype_id', 'created_by'], 'default', 'value' => null],
            [['approval_id', 'company_id', 'person_id_sender', 'person_id_approval', 'data_id', 'apptype_id', 'created_by'], 'integer'],
            [['comment', 'justification'], 'string'],
            [['created_time'], 'safe'],
            [['company_code'], 'string', 'max' => 100],
            [['company_name', 'person_name_sender', 'person_name_approval', 'apptype_name'], 'string', 'max' => 255],
            [['employee_id_sender', 'employee_id_approval'], 'string', 'max' => 50],
            [['app_status'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'approval_id' => 'Approval ID',
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'person_id_sender' => 'Person Id Sender',
            'employee_id_sender' => 'Employee Id Sender',
            'person_name_sender' => 'Person Name Sender',
            'person_id_approval' => 'Person Id Approval',
            'employee_id_approval' => 'Employee Id Approval',
            'person_name_approval' => 'Person Name Approval',
            'data_id' => 'Data ID',
            'apptype_id' => 'TIPE PENGAJUAN',
            'apptype_name' => 'Apptype Name',
            'comment' => 'Comment',
            'justification' => 'Justification',
            'app_status' => 'App Status',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
        ];
    }
	
	public function customAttributeLabelsNeed()
    {
        return [
			[
				'name'=> [
					'pilih' => 'CHOOSE',
				],
				'class'=>'check'
			],
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
					'apptype_id' => 'TIPE PENGAJUAN',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'person_id_sender' => 'PENGAJU',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'created_time' => 'TGL PENGAJUAN',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'comment' => 'KOMENTAR',
				],
				'class'=>'freetext'
			]
		];
	}
	
	public function customAttributeLabelsExceptNeed()
    {
        return [
			[
				'name'=> [
					'pilih' => 'CHOOSE',
				],
				'class'=>'check'
			],
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
					'apptype_id' => 'TIPE PENGAJUAN',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'person_id_sender' => 'PENGAJU',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'person_id_approval' => 'PEMROSES',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'created_time' => 'TGL PROSES',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'comment' => 'KOMENTAR',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'justification' => 'JUSTIFIKASI',
				],
				'class'=>'freetext'
			]
		];
	}

    /**
     * {@inheritdoc}
     * @return VListApprovalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VListApprovalQuery(get_called_class());
    }
}
