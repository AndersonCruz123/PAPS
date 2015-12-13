<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "relatorio".
 *
 * @property integer $id
 */
class Relatorio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tipo;
    public $dataInicial;
    public $dataFinal;
    public $mes;
    public $ano;
    public $radiobutton;


    public static function tableName()
    {
        return 'relatorio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo', 'mes', 'ano', 'radiobutton'], 'integer'],
            [['dataInicial', 'dataFinal'], 'safe'],         
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'radiobutton' => 'Selecione o período do relatório',
        ];
    }
}