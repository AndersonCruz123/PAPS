<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $cpf;
    public $senha;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // cpf and senha are both required
            [['cpf', 'senha'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // senha is validated by validatesenha()
            ['senha', 'validatesenha'],
        ];
    }

    /**
     * Validates the senha.
     * This method serves as the inline validation for senha.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatesenha($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatesenha($this->senha)) {
                $this->addError($attribute, 'Incorrect cpf or senha.');
            }
        }
    }

    /**
     * Logs in a user using the provided cpf and senha.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[cpf]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findBycpf($this->cpf);
        }

        return $this->_user;
    }
}
