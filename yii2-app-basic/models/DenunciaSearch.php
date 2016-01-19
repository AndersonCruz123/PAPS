<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Denuncia;

/**
 * DenunciaSearch represents the model behind the search form about `app\models\Denuncia`.
 */
class DenunciaSearch extends Denuncia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idDenuncia', 'status'], 'safe'],
            [['descricao', 'detalheLocal', 'idLocal', 'periodo', 'hora', 'data'], 'safe'],
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
        $query = Denuncia::find();

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
        $databkp = $this->data;
        $periodobkp = $this->periodo;
        if ($this->status!=null) {
            if (strcmp($this->status, 'Não verificada') == 0)$this->status = 1;
            elseif (strcmp($this->status, 'Verdadeira') == 0)$this->status = 2;
            elseif (strcmp($this->status, 'Falsa') == 0)$this->status = 3;
        }

        if ($this->periodo!=null) {
            if (strcmp($this->periodo, 'Manhã') == 0)$this->periodo = 1;
            elseif (strcmp($this->periodo, 'Tarde') == 0)$this->periodo = 2;
            elseif (strcmp($this->periodo, 'Noite') == 0)$this->periodo = 3;
            elseif (strcmp($this->periodo, 'Madrugada') == 0)$this->periodo = 4;
        }
        if ($this->data!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->data);
            $this->data = $ano.'-'.$mes.'-'.$dia;
         }

        $query->andFilterWhere([
            'idDenuncia' => $this->idDenuncia,
            'data' => $this->data,
            'status' => $this->status,
            'periodo' => $this->periodo,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'idSubLocal', $this->idSubLocal])
            ->andFilterWhere(['like', 'hora', $this->hora]);

        $this->status = $statusbkp;
        $this->data = $databkp;
        $this->periodo = $periodobkp;
        return $dataProvider;
    }

    public function naoVerificadas($params)
    {
         $query = Denuncia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $databkp = $this->data;
        $periodobkp = $this->periodo;

        if ($this->periodo!=null) {
            if (strcmp($this->periodo, 'Manhã') == 0)$this->periodo = 1;
            elseif (strcmp($this->periodo, 'Tarde') == 0)$this->periodo = 2;
            elseif (strcmp($this->periodo, 'Noite') == 0)$this->periodo = 3;
            elseif (strcmp($this->periodo, 'Madrugada') == 0)$this->periodo = 4;
        }
        if ($this->data!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->data);
            $this->data = $ano.'-'.$mes.'-'.$dia;
         }

        $query->andFilterWhere([
            'idDenuncia' => $this->idDenuncia,
            'data' => $this->data,
            'status' => 1,
            'periodo' => $this->periodo,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'hora', $this->hora]);

        $this->data = $databkp;
        $this->periodo = $periodobkp;
        return $dataProvider;
    }

}
