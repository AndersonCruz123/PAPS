<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\base\Model;
/**
 * This is the model class for table "foto".
 *
 * @property integer $idFoto
 * @property string $comentario
 * @property integer $idOcorrencia
 * @property integer $idDenuncia
 * @property string $endereco
 *
 * @property Denuncia $idDenuncia0
 * @property Ocorrencia $idOcorrencia0
 */
class Foto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['endereco'], 'required','message'=>'Este campo é obrigatório'],
            [['idOcorrencia', 'idDenuncia'], 'integer'],
            [['comentario'], 'string', 'max' => 500],
            [['endereco'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idFoto' => 'Id Foto',
            'comentario' => 'Comentario',
            'idOcorrencia' => 'Id Ocorrencia',
            'idDenuncia' => 'Id Denuncia',
            'endereco' => 'Endereco',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDenuncia0()
    {
        return $this->hasOne(Denuncia::className(), ['idDenuncia' => 'idDenuncia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOcorrencia0()
    {
        return $this->hasOne(Ocorrencia::className(), ['idOcorrencia' => 'idOcorrencia']);
    }
}
