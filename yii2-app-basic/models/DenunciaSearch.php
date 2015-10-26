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
            [['idDenuncia', 'status'], 'integer'],
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

        $query->andFilterWhere([
            'idDenuncia' => $this->idDenuncia,
            'data' => $this->data,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'hora', $this->hora]);

        return $dataProvider;
    }
}
