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
            [['descricao', 'local', 'data', 'hora'], 'safe'],
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

        if ($this->status!=null) {
            if (strcmp($this->status, 'Não verificada') == 0)$this->status = 1;
            elseif (strcmp($this->status, 'Verdadeira') == 0)$this->status = 2;
            elseif (strcmp($this->status, 'Falsa') == 0)$this->status = 3;
        }

        if ($this->data!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->data);
            $this->data = $ano.'-'.$mes.'-'.$dia;
         }

        $query->andFilterWhere([
            'idDenuncia' => $this->idDenuncia,
            'data' => $this->data,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'hora', $this->hora]);

        $this->status = $statusbkp;
        $this->data = $databkp;

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

        $query->andFilterWhere([
            'idDenuncia' => $this->idDenuncia,
            'descricao' => $this->descricao,
            'local' => $this->local,
            'data' => $this->data,
            'hora' => $this->hora,
            'status' => 1,
 
        ]);

/*        $query->andFilterWhere(['like', 'periodo', $this->periodo])
            ->andFilterWhere(['like', 'detalheLocal', $this->detalheLocal])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'procedimento', $this->procedimento])
            ->andFilterWhere(['like', 'cpfUsuario', $this->cpfUsuario]);*/

        return $dataProvider;
    }

}
