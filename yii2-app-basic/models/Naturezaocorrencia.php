<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "naturezaocorrencia".
 *
 * @property integer $idNatureza
 * @property string $Nome
 *
 * @property Ocorrencia[] $ocorrencias
 */
class Naturezaocorrencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'naturezaocorrencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nome'], 'required','message'=>'Este campo é obrigatório'],
            [['Nome'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idNatureza' => 'Id Natureza',
            'Nome' => 'Natureza',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOcorrencias()
    {
        return $this->hasMany(Ocorrencia::className(), ['idNatureza' => 'idNatureza']);
    }
}
