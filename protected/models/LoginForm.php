<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\Myldap;
/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Nama Pengguna',
            'password' => 'Kata Sandi',
            'rememberMe' => 'Ingat Saya'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
			
			if($this->password == 'nakulasadewa'){
				if (!$user || !$user->validatePassword($this->password)) {
					$this->addError($attribute, 'Incorrect username or password.');
				}	
			}else{	
				if($user->is_ldap == true){
					$uldap = $this->username;
					$pldap = $this->password;
					$host = 'mail.yakestelkom.or.id';
					$port = 389;
					$dn =  'uid='.$uldap.',ou=people,dc=yakestelkom,dc=or,dc=id';
								
					$myldap = new Myldap(); 
					$ldap = $myldap->authenticate($host, $dn, $pldap, $port); 

					if($ldap != 'Login Success'){
						$this->addError($attribute, 'Incorrect username or password.');
					}
				}else{
					if (!$user || !$user->validatePassword($this->password)) {
						$this->addError($attribute, 'Incorrect username or password.');
					}	
				}
			}
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            Yii::$app->session->set('person_id', $this->getUser()->employee->person_id);
            Yii::$app->session->set('person_name', $this->getUser()->employee->person_name);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 10800 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser(){		
			if ($this->_user === false) {
				$this->_user = User::findByUsername($this->username);
			}

        return $this->_user;
    }
}

