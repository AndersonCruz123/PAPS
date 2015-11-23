<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sublocal".
 *
 * @property integer $idSubLocal
 * @property string $Nome
 * @property integer $idLocal
 *
 * @property Ocorrencia[] $ocorrencias
 * @property Local $idLocal0
 */
class Sublocal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $idLocalbkp;
    
    public static function tableName()
    {
        return 'sublocal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nome', 'idLocal'], 'required','message'=>'Este campo é obrigatório'],
            [['idLocal'], 'integer'],
            [['Nome'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idSubLocal' => 'Id Sub Local',
            'Nome' => 'Nome',
            'idLocal' => 'Local',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOcorrencias()
    {
        return $this->hasMany(Ocorrencia::className(), ['idSubLocal' => 'idSubLocal']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdLocal0()
    {
        return $this->hasOne(Local::className(), ['idLocal' => 'idLocal']);
    }
    public function afterFind(){
        $this->idLocalbkp = $this->idLocal;
        return $this->idLocal = Local::findOne($this->idLocal)->Nome;
    }
}
