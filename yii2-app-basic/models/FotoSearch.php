<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Foto;

/**
 * FotoSearch represents the model behind the search form about `app\models\Foto`.
 */
class FotoSearch extends Foto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idFoto', 'idOcorrencia', 'idDenuncia'], 'integer'],
            [['comentario', 'endereco'], 'safe'],
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
        $query = Foto::find();

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
            'idFoto' => $this->idFoto,
            'idOcorrencia' => $this->idOcorrencia,
            'idDenuncia' => $this->idDenuncia,
        ]);

        $query->andFilterWhere(['like', 'comentario', $this->comentario])
            ->andFilterWhere(['like', 'endereco', $this->endereco]);

        return $dataProvider;
    }
}
