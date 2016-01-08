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
            [['cpf'], 'string', 'max' => 11],
            [['nome'], 'string', 'max' => 300],
            [['email'], 'string', 'max' => 150],
            [['senha'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
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
        
        $key = substr($key, 0, 10) . '_' . rand(1,10000);
        
        $password = $key ;

        $usuario->senha =$password;

        $usuario->idTipoUsuario = $usuario->idTipoUsuariobkp;

        $usuario->confirmarSenha = $password;

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
