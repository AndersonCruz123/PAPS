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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cpf', 'email', 'senha', 'idTipoUsuario'], 'required'],
            [['idTipoUsuario'], 'integer'],
            [['cpf'], 'string', 'max' => 11],
            [['email'], 'string', 'max' => 50],
            [['senha'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cpf' => 'Cpf',
            'email' => 'Email',
            'senha' => 'Senha',
            'idTipoUsuario' => 'Id Tipo Usuario',
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
        return $this->idTipoUsuario = Tipousuario::findOne($this->idTipoUsuario)->funcao;
    }

}
