<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * FamilyTypeSearch represents the model behind the search form of `app\models\FamilyType`.
 */
class UserChange extends User
{
	public $old_password;
	public $new_password;
	public $repeat_password;
    
	public function rules(){
		return [
			[['old_password','new_password','repeat_password'],'required'],
			['old_password','findPasswords'],
			[['username'], 'string', 'max' => 100],
			[['new_password', 'repeat_password'], 'string', 'min' => 8],
			['repeat_password','compare','compareAttribute'=>'new_password'],
		];
	}
	
	public function findPasswords($attribute, $params){
		$user = User::find()->where(['person_id'=>Yii::$app->user->identity->employee->person_id])->one();
		$password = $user->password;
		if($password != $this->old_password){
			$this->addError($attribute,'Old password is incorrect');
		}
	}

	public function attributeLabels(){
		return [
			'old_password'=>'Old Password',
			'new_password'=>'New Password',
			'repeat_password'=>'Repeat Password',
		];
	}

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
