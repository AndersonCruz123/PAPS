<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;
use app\models\Naturezaocorrencia;
use app\models\NaturezaocorrenciaSearch;
/**
 * OcorrenciaSearch represents the model behind the search form about `app\models\Ocorrencia`.
 */
class OcorrenciaSearch extends Ocorrencia
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['idOcorrencia', 'status', 'idCategoria', 'idSubLocal', 'idNatureza'], 'safe'],
            [['data', 'hora', 'periodo', 'detalheLocal', 'descricao', 'procedimento', 'dataConclusao', 'cpfUsuario'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Ocorrencia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $statusbkp = $this->status;
        $idCategoriabkp = $this->idCategoria;
        $idNaturezabkp = $this->idNatureza;
        $periodobkp = $this->periodo;
        $databkp = $this->data;

        if ($this->data!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->data);
            $this->data = $ano.'-'.$mes.'-'.$dia;
         }

        if ($this->dataConclusao!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->dataConclusao);
            $this->dataConclusao = $ano.'-'.$mes.'-'.$dia;
         }
        
         if ($this->status!=null) {
            if (strcmp($this->status, 'Aberto') == 0)$this->status = 1;
            elseif (strcmp($this->status, 'Solucionado') == 0)$this->status = 2;
            elseif (strcmp($this->status, 'Não Solucionado') == 0)$this->status = 3;
        }

        if ($this->periodo!=null) {
            if (strcmp($this->periodo, 'Manhã') == 0)$this->periodo = 1;
            elseif (strcmp($this->periodo, 'Tarde') == 0)$this->periodo = 2;
            elseif (strcmp($this->periodo, 'Noite') == 0)$this->periodo = 3;
            elseif (strcmp($this->periodo, 'Madrugada') == 0)$this->periodo = 4;
        }
            $Natureza = Naturezaocorrencia::find()->where(['nome' => $this->idNatureza])->One();
        if ($Natureza!=null) {
            $this->idNatureza = $Natureza->idNatureza;
        }
            $Categoria = Categoria::find()->where(['nome' => $this->idCategoria])->One();
        if ($Categoria!=null) {
            $this->idCategoria = $Categoria->idCategoria;
        }
        //$this->idCategoria = Categoria::findOne($this->idCategoria)->Nome;

        $query->andFilterWhere([
            'idOcorrencia' => $this->idOcorrencia,
            'status' => $this->status,
            'data' => $this->data,
            'hora' => $this->hora,
            'dataConclusao' => $this->dataConclusao,
            'idCategoria' => $this->idCategoria,
            'idSubLocal' => $this->idSubLocal,
            'idNatureza' => $this->idNatureza,
        ]);

        $query->andFilterWhere(['like', 'periodo', $this->periodo])
            ->andFilterWhere(['like', 'detalheLocal', $this->detalheLocal])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'procedimento', $this->procedimento])
            ->andFilterWhere(['like', 'cpfUsuario', $this->cpfUsuario]);

        $this->status = $statusbkp;
        $this->idCategoria = $idCategoriabkp;
        $this->idNatureza = $idNaturezabkp;
        $this->periodo = $periodobkp;
        $this->data = $databkp;
        return $dataProvider;
    }


        public function emAberto($params)
    {
        $query = Ocorrencia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $idCategoriabkp = $this->idCategoria;
        $idNaturezabkp = $this->idNatureza;
        $periodobkp = $this->periodo;
        $databkp = $this->data;

        if ($this->data!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->data);
            $this->data = $ano.'-'.$mes.'-'.$dia;
         }

        if ($this->dataConclusao!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->dataConclusao);
            $this->dataConclusao = $ano.'-'.$mes.'-'.$dia;
         }

        if ($this->periodo!=null) {
            if (strcmp($this->periodo, 'Manhã') == 0)$this->periodo = 1;
            elseif (strcmp($this->periodo, 'Tarde') == 0)$this->periodo = 2;
            elseif (strcmp($this->periodo, 'Noite') == 0)$this->periodo = 3;
            elseif (strcmp($this->periodo, 'Madrugada') == 0)$this->periodo = 4;
        }
            $Natureza = Naturezaocorrencia::find()->where(['nome' => $this->idNatureza])->One();
        if ($Natureza!=null) {
            $this->idNatureza = $Natureza->idNatureza;
        }
            $Categoria = Categoria::find()->where(['nome' => $this->idCategoria])->One();
        if ($Categoria!=null) {
            $this->idCategoria = $Categoria->idCategoria;
        }
        //$this->idCategoria = Categoria::findOne($this->idCategoria)->Nome;

        $query->andFilterWhere([
            'idOcorrencia' => $this->idOcorrencia,
            'status' => 1,
            'data' => $this->data,
            'hora' => $this->hora,
            'dataConclusao' => $this->dataConclusao,
            'idCategoria' => $this->idCategoria,
            'idSubLocal' => $this->idSubLocal,
            'idNatureza' => $this->idNatureza,
        ]);

        $query->andFilterWhere(['like', 'periodo', $this->periodo])
            ->andFilterWhere(['like', 'detalheLocal', $this->detalheLocal])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'procedimento', $this->procedimento])
            ->andFilterWhere(['like', 'cpfUsuario', $this->cpfUsuario]);

    
        $this->idCategoria = $idCategoriabkp;
        $this->idNatureza = $idNaturezabkp;
        $this->periodo = $periodobkp;
        $this->data = $databkp;
        return $dataProvider;

    }
}
