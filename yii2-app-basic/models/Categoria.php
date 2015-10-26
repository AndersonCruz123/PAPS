<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoria".
 *
 * @property integer $idCategoria
 * @property string $Nome
 *
 * @property Ocorrencia[] $ocorrencias
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nome'], 'required'],
            [['Nome'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idCategoria' => 'Id Categoria',
            'Nome' => 'Nome',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOcorrencias()
    {
        return $this->hasMany(Ocorrencia::className(), ['idCategoria' => 'idCategoria']);
    }
}
