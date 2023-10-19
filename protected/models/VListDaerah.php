<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_list_daerah".
 *
 * @property int|null $master_id
 * @property string|null $key
 * @property string|null $code
 * @property string|null $name
 * @property string|null $description
 * @property int|null $order
 */
class VListDaerah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_list_daerah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id', 'order'], 'default', 'value' => null],
            [['master_id', 'order'], 'integer'],
            [['description'], 'string'],
            [['key', 'code'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'master_id' => 'Master ID',
            'key' => 'Key',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'order' => 'Order',
        ];
    }

    /**
     * {@inheritdoc}
     * @return VListDaerahQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VListDaerahQuery(get_called_class());
    }
}
