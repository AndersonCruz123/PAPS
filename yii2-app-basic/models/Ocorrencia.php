<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ocorrencia".
 *
 * @property integer $idOcorrencia
 * @property integer $status
 * @property string $data
 * @property string $hora
 * @property string $periodo
 * @property string $detalheLocal
 * @property string $descricao
 * @property string $procedimento
 * @property string $dataConclusao
 * @property integer $idCategoria
 * @property integer $idSubLocal
 * @property integer $idNatureza
 * @property string $cpfUsuario
 *
 * @property Foto[] $fotos
 * @property Categoria $idCategoria0
 * @property Naturezaocorrencia $idNatureza0
 * @property Sublocal $idSubLocal0
 * @property Usuario $cpfUsuario0
 */
class Ocorrencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ocorrencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'data', 'hora', 'periodo', 'detalheLocal', 'descricao', 'idCategoria', 'idSubLocal', 'idNatureza', 'cpfUsuario'], 'required','message'=>'Este campo é obrigatório'],
            [['status', 'idCategoria', 'idSubLocal', 'idNatureza'], 'integer'],
            [['data', 'hora', 'dataConclusao'], 'safe'],
            [['descricao', 'procedimento'], 'string'],
            [['periodo'], 'string', 'max' => 6],
            [['detalheLocal'], 'string', 'max' => 120],
            [['cpfUsuario'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idOcorrencia' => 'Id Ocorrencia',
            'status' => 'Status',
            'data' => 'Data',
            'hora' => 'Hora',
            'periodo' => 'Período',
            'detalheLocal' => 'Detalhamento do Local',
            'descricao' => 'Descrição',
            'procedimento' => 'Procedimento',
            'dataConclusao' => 'Data de Conclusão',
            'idCategoria' => 'Categoria',
            'idSubLocal' => 'Sublocal',
            'idNatureza' => 'Natureza da Ocorrência',
            'cpfUsuario' => 'CPF',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFotos()
    {
        return $this->hasMany(Foto::className(), ['idOcorrencia' => 'idOcorrencia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCategoria0()
    {
        return $this->hasOne(Categoria::className(), ['idCategoria' => 'idCategoria']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdNatureza0()
    {
        return $this->hasOne(Naturezaocorrencia::className(), ['idNatureza' => 'idNatureza']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSubLocal0()
    {
        return $this->hasOne(Sublocal::className(), ['idSubLocal' => 'idSubLocal']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpfUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['cpf' => 'cpfUsuario']);
    }
}
