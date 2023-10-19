<?php

namespace app\models;

use Yii;
use app\components\Logic;

/**
 * This is the model class for table "m_master".
 *
 * @property int $master_id
 * @property int|null $parent_id
 * @property string $key
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property bool|null $is_others
 * @property bool|null $is_link
 * @property int $order
 * @property bool $is_active
 * @property int $created_by
 * @property string $created_time
 * @property int|null $updated_by
 * @property string|null $updated_time
 * @property int|null $deleted_by
 * @property string|null $deleted_time
 * @property string|null $slug_url
 */
class MMaster extends \yii\db\ActiveRecord
{
	public $data_file;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'order', 'created_by', 'updated_by', 'deleted_by'], 'default', 'value' => null],
            [['parent_id', 'order', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['key', 'code', 'name', 'order', 'created_by', 'created_time'], 'required'],
            [['description'], 'string'],
			['key', 'unique', 'targetAttribute' => ['code', 'key' => 'name']],
            [['is_others', 'is_link', 'is_active'], 'boolean'],
            [['created_time', 'updated_time', 'deleted_time'], 'safe'],
			[['data_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, ico' , 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2],
            [['key', 'code', 'table_name'], 'string', 'max' => 100],
            [['name', 'slug_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'master_id' => 'Master ID',
            'parent_id' => 'Parent ID',
            'key' => 'Key',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'is_others' => 'Is Others',
            'is_link' => 'Is Link',
            'order' => 'Order',
            'is_active' => 'Is Active',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
            'deleted_by' => 'Deleted By',
            'deleted_time' => 'Deleted Time',
            'slug_url' => 'Slug Url',
            'table_name' => 'Table Name',
        ];
    }
	
	public function customAttributeLabels()
    {
        return [
			[
				'name'=> [
					'no' => 'No',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'action' => 'Action',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'code' => 'Code',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'name' => 'Name',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'description' => 'Description',
				],
				'class'=>'freetext'
			],
			[
				'name'=> [
					'is_others' => 'Is Others ?',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'is_link' => 'Is Link ?',
				],
				'class'=>'dpdown'
			],
			[
				'name'=> [
					'order' => 'Order',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'created_by' => 'Created By',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'created_time' => 'Created Time',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'updated_by' => 'Updated By',
				],
				'class'=>'nofilter'
			],
			[
				'name'=> [
					'updated_time' => 'Updated Time',
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
	
	public function getLastorder($key) {
		$model = MMaster::find()->select(['order'])->andWhere(['is_active'=>true, 'key'=>$key])->orderBy(['order'=>SORT_DESC])->one();
		if(!empty($model)){
			return $model->order;
		}else{
			return 1;
		}
    }
	
    /**
     * {@inheritdoc}
     * @return MMasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MMasterQuery(get_called_class());
    }
	
	public function upload($old_file = null, $company_name = null, $folder = null)
    {
		if(!empty($this->data_file)){
			if(!empty($old_file)){
				unlink(\Yii::getAlias('@webroot').$old_file);
			}
			$pathcompany = \Yii::getAlias('@webroot/storage/company').'/'.Logic::slugify($company_name);
			if(file_exists($pathcompany)){
				$pathroot = $pathcompany.'/'.$folder;
				$createroot = mkdir($pathroot);
				if($createroot == true){
					$modroot = chmod($pathroot, 0777);
				}
			}else{
				$createcompany = mkdir($pathcompany);
				if($createcompany == true){
					$modcompany = chmod($pathcompany, 0777);
					if($modcompany == true){
						$pathroot = $pathcompany.'/'.$folder;
						$createroot = mkdir($pathroot);
						if($createroot == true){
							$modroot = chmod($pathroot, 0777);
						}
					}
				}
			}
			
			$pathabsolute = '/storage/company/'.Logic::slugify($company_name).'/'.$folder;
			
			$file_name = Logic::slugify($this->data_file->baseName);
			
			$this->data_file->saveAs($pathroot.'/'.$file_name.'.'. $this->data_file->extension);
			$this->name = $pathabsolute.'/'.$file_name.'.'. $this->data_file->extension;
		}else{
			$this->name = $old_file;
		}
			
        if ($this->validate()) {
            return ['data'=>true];
        } else {
            return [
				'data'=>false,
				'errors'=>$this->errors
			];
        }
    }
}
