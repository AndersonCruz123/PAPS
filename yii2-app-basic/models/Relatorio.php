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
            [['radiobutton'], 'required', 'message' => 'Este campo é obrigatório'],
            [['tipo', 'mes', 'ano', 'radiobutton', 'periodo', 'idLocal', 'idNatureza', 'idCategoria', 'status'], 'integer'],
            [['dataInicial', 'dataFinal', 'mesAno'], 'validatedata'],         
        ];
    }

    /**
     * @inheritdoc
     */

   public function validatedata()
    {

        if ($this->radiobutton == 1 && $this->mesAno == null){ 
            $this->addError('mesAno', 'Insira um mês e ano');            
            return false;
        }
        if ($this->radiobutton == 2 && $this->dataInicial == null) { 
            $this->addError('dataInicial', 'Insira uma data inicial');
            if ($this->radiobutton == 2 && $this->dataFinal == null) { 
                $this->addError('dataFinal', 'Insira uma data inicial');    
            }
            return false;
        }
        if ($this->radiobutton == 2 && $this->dataFinal == null) { 
            $this->addError('dataFinal', 'Insira uma data inicial');    
            return false;
        }
        return true;
     }

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
