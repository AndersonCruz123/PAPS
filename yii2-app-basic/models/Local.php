<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "local".
 *
 * @property integer $idLocal
 * @property string $Nome
 *
 * @property Sublocal[] $sublocals
 */
class Local extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'local';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nome'], 'required','message'=>'Este campo é obrigatório'],
            [['Nome'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idLocal' => 'id Local',
            'Nome' => 'Nome',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSublocals()
    {
        return $this->hasMany(Sublocal::className(), ['idLocal' => 'idLocal']);
    }
}
