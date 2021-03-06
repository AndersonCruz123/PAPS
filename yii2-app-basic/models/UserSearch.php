<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cpf', 'email', 'senha', 'nome'], 'safe'],
            [['idTipoUsuario'], 'integer'],
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
        $query = User::find();

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
            'idTipoUsuario' => $this->idTipoUsuario,
        ]);

        $query->andFilterWhere(['like', 'cpf', $this->cpf])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'senha', $this->senha]);

        return $dataProvider;
    }
}
