<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sublocal;

/**
 * SublocalSearch represents the model behind the search form about `app\models\Sublocal`.
 */
class SublocalSearch extends Sublocal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idSubLocal', 'idLocal'], 'integer'],
            [['Nome'], 'safe'],
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
        $query = Sublocal::find();

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
            'idSubLocal' => $this->idSubLocal,
            'idLocal' => $this->idLocal,
        ]);

        $query->andFilterWhere(['like', 'Nome', $this->Nome]);

        return $dataProvider;
    }
}
