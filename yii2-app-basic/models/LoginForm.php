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
            [['cpf', 'senha'], 'required','message'=>'Este campo é obrigatório'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // senha is validated by validatesenha()
            ['cpf', 'validacpf'],
            ['senha', 'validatesenha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'cpf' => 'CPF',
            'rememberMe' => 'Lembrar-me',
        ];
    }

public function validacpf($attribute, $params)
{	// Verifiva se o número digitado contém todos os digitos
    $this->cpf = str_pad(ereg_replace('[^0-9]', '', $this->cpf), 11, '0', STR_PAD_LEFT);
	
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($this->cpf) != 11)
	{
	$this->addError($attribute, 'Insira um CPF válido');
    }
	else
	{   // Calcula os números para verificar se o CPF é verdadeiro
            $user = User::findBycpf($this->cpf);
            if ($user==null) $this->addError($attribute, 'Este CPF não possui cadastro no sistema');
    }
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
            $this->senha = md5($this->senha);

            if (!$user || !$user->validatesenha($this->senha)) {
                $this->addError($attribute, 'Senha incorreta');
                $this->senha = null;
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
