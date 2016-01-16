<?php



namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $cpf
 * @property string $email
 * @property string $senha
 * @property integer $idTipoUsuario
 *
 * @property Ocorrencia[] $ocorrencias
 * @property Tipousuario $idTipoUsuario0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public $confirmarSenha;
    public $novaSenha;
    public $idTipoUsuariobkp;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cpf', 'email', 'senha', 'confirmarSenha', 'idTipoUsuario', 'nome'], 'required','message'=>'Este campo é obrigatório'],
            [['idTipoUsuario'], 'integer'],
            [['cpf'], 'validacpf'],
            [['nome'], 'string', 'max' => 300],
            [['confirmarSenha'], 'compare', 'compareAttribute' => 'senha', 'message'=> 'Deve ser exatamente igual ao campo senha'],            
            [['email'], 'email', 'message'=>'Este email é inválido'],
            [['senha'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
public function validacpf($attribute, $params)
{	// Verifiva se o número digitado contém todos os digitos
    $this->cpf = str_pad(ereg_replace('[^0-9]', '', $this->cpf), 11, '0', STR_PAD_LEFT);
	
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($this->cpf) != 11 || $this->cpf == '00000000000' || $this->cpf == '11111111111' || $this->cpf == '22222222222' 
    	|| $this->cpf == '33333333333' || $this->cpf == '44444444444' || $this->cpf == '55555555555' || $this->cpf == '66666666666' 
    	|| $this->cpf == '77777777777' || $this->cpf == '88888888888' || $this->cpf == '99999999999')
	{
	$this->addError($attribute, 'Insira um CPF válido');
    }
	else
	{   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $this->cpf{$c} * (($t + 1) - $c);
            }
 
            $d = ((10 * $d) % 11) % 10;
 
            if ($this->cpf{$c} != $d) {
				$this->addError($attribute, 'Insira um CPF válido');
            }
        }
    }
}

    public function attributeLabels()
    {
        return [
            'cpf' => '*CPF',
            'email' => '*Email',
            'senha' => '*Senha',
            'nome' => '*Nome',
            'confirmarSenha' => '*Confirmar Senha',
            'idTipoUsuario' => '*Tipo de Usuário',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public static function findByCpf($cpf)
    {

      if (($model = User::findOne($cpf)) !== null) {
            return $model;
        } else {
            //throw new NotFoundHttpException('The requested page does not exist.');
        }
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return $this->senha === $authKey;
    }

    public function validatesenha($password)
    {
        return $this->senha === $password;
    }

    public static function findIdentity($id)
    {
        $dbUser = self::find()
            ->where(["cpf" => $id])
            ->one();
        if (!count($dbUser)) {
            return null;
        }
        return $dbUser;
  
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public function getId()
    {
        return $this->cpf;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->cpf.'100';
    }




    public function getOcorrencias()
    {
        return $this->hasMany(Ocorrencia::className(), ['cpfUsuario' => 'cpf']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoUsuario0()
    {
        return $this->hasOne(Tipousuario::className(), ['idTipo' => 'idTipoUsuario']);
    }

    public function afterFind(){
        $this->idTipoUsuariobkp = $this->idTipoUsuario;
        $this->confirmarSenha = $this->senha;
        $this->idTipoUsuario = Tipousuario::findOne($this->idTipoUsuario)->funcao;
    }

 public function senhaAleatoria($cpf)
    {
        
        $usuario = new User();
        $usuario = User::findOne($cpf);


        //return print_r($user);

        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        
        $key = substr(str_shuffle(str_repeat($chars, 5)), 0, strlen($chars) );
        
        $key = substr($key, 0, 5) . '_' . rand(1,10000);
        
        $password = $key;

        $usuario->senha = md5($password);

        $usuario->idTipoUsuario = $usuario->idTipoUsuariobkp;

        $usuario->confirmarSenha = md5($password);

        if ($usuario->save()){
                print('CONSEGUISAVE');
            }else {
                print('NAOCONSEGUI SAVE\n');
                print('cpf:'.$usuario->cpf.'\n');
                print('senha:'.$usuario->senha.'\n');
                print('email:'.$usuario->email.'\n');
                print('idTipoUsuario:'.$usuario->idTipoUsuario.'\n');
                }

        return $password ; //sem ta criptografado 
    } 

}
