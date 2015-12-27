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
    public $mesAno;
    public $radiobutton;
    public $idLocal;
    public $idNatureza;
    public $idCategoria;
    public $status;
    public $periodo;
    public $mes;
    public $ano;

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
            [['tipo', 'mes', 'ano', 'radiobutton', 'periodo', 'idLocal', 'idNatureza', 'idCategoria', 'status'], 'integer'],
            [['dataInicial', 'dataFinal', 'mesAno'], 'safe'],         
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'radiobutton' => 'Selecione o período do relatório',
            'idLocal' => 'Local',
            'idNatureza' => 'Natureza',
            'idCategoria' => 'Categoria',
            'status' => 'Status',
            'periodo' => 'Período',
            'mesAno' => 'Mês e ano'
        ];
    }
}
