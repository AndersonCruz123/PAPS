<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property string $cpf
 * @property string $email
 * @property string $senha
 * @property integer $idTipoUsuario
 *
 * @property Ocorrencia[] $ocorrencias
 * @property Tipousuario $idTipoUsuario0
 */
class Usuario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cpf', 'email', 'senha', 'idTipoUsuario'], 'required','message'=>'Este campo é obrigatório'],
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
}
