<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;

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
            [['idOcorrencia', 'status', 'idCategoria', 'idSubLocal', 'idNatureza'], 'integer'],
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

        return $dataProvider;
    }
}
