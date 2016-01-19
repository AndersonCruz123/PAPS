<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;
use app\models\Naturezaocorrencia;
use app\models\NaturezaocorrenciaSearch;
use app\models\Sublocal;
use app\models\SublocalSearch;
use yii\helpers\ArrayHelper;
/**
 * OcorrenciaSearch represents the model behind the search form about `app\models\Ocorrencia`.
 */
class OcorrenciaSearch extends Ocorrencia
{
    /**
     * @inheritdoc
     */
    public $dataInicial;
    public $dataFinal;
    public $idLocal;

    public function rules()
    {
        return [
            [['idOcorrencia', 'status', 'idCategoria','idLocal', 'idNatureza', 'dataInicial','dataFinal'], 'safe'],
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

    public function relatorio($params)
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

        $databkp = $this->data;

        if ($this->dataInicial!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->dataInicial);
            $this->dataInicial = $ano.'-'.$mes.'-'.$dia;
         }

        if ($this->dataFinal!=null){
             list ($dia, $mes, $ano) = split ('[/]', $this->dataFinal);
            $this->dataFinal = $ano.'-'.$mes.'-'.$dia;
         }
        
 //       if ($this->idLocal!=null)
         $sublocal = Sublocal::find()->where(['=', 'idLocal', $this->idLocal])->all();
        //$model->idSubLocal = $sublocal->Nome;
  
         
        $query->andFilterWhere([
            'status' => $this->status,
             'idCategoria' => $this->idCategoria,
             'idNatureza' => $this->idNatureza,
             'periodo' => $this->periodo,          
    //         ''   
        ]);
       
    $query->andFilterWhere(['>=', 'data', $this->dataInicial]);
    $query->andFilterWhere(['<=', 'data', $this->dataFinal]);

    if ($this->idLocal!=null) {
        $connection = \Yii::$app->db;
        $stringsql = "SELECT idSubLocal as idsublocal FROM sublocal WHERE idLocal = ".$this->idLocal;
        $sqlOcorrencia = $connection->createCommand($stringsql);      
        $rstocorencia = $sqlOcorrencia->queryAll();

        $arraysublocal = array();
            $i=0;
            foreach ($rstocorencia as $reg):            
                $arraysublocal[$i] = $reg['idsublocal'];
                $i=$i+1;
                endforeach;

        $query->andFilterWhere(['IN', 'idSubLocal', $arraysublocal]);
        }
        return $dataProvider;
    }
  /*    public function relatorio($params)
    {
        $query = Ocorrencia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

     //   $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $idCategoriabkp = $params->idCategoria;
        $idNaturezabkp = $params->idNatureza;
        $periodobkp = $params->periodo;
        $databkp = $params->dataInicial;

        $this->idCategoria = $params->idCategoria;
        $this->periodo = $params->periodo;

        if ($params->dataInicial!=null){
             list ($dia, $mes, $ano) = split ('[/]', $params->dataInicial);
            $params->dataInicial = $ano.'-'.$mes.'-'.$dia;
         }

        if ($params->dataFinal!=null){
             list ($dia, $mes, $ano) = split ('[/]', $params->dataFinal);
            $params->dataFinal = $ano.'-'.$mes.'-'.$dia;
         }

        if ($params->periodo!=null) {
            if (strcmp($params->periodo, 'Manhã') == 0)$params->periodo = 1;
            elseif (strcmp($params->periodo, 'Tarde') == 0)$params->periodo = 2;
            elseif (strcmp($params->periodo, 'Noite') == 0)$params->periodo = 3;
            elseif (strcmp($params->periodo, 'Madrugada') == 0)$params->periodo = 4;
        }
            $Natureza = Naturezaocorrencia::find()->where(['nome' => $params->idNatureza])->One();
        if ($Natureza!=null) {
            $params->idNatureza = $Natureza->idNatureza;
        }
            $Categoria = Categoria::find()->where(['nome' => $params->idCategoria])->One();
        if ($Categoria!=null) {
            $params->idCategoria = $Categoria->idCategoria;
        }
        //$this->idCategoria = Categoria::findOne($this->idCategoria)->Nome;

        $query->andFilterWhere([
//            'idOcorrencia' => $params->idOcorrencia,
            'status' => $params->status,
            'data' => $params->dataInicial,
           // 'hora' => $params->hora,
         //   'dataConclusao' => $params->dataFinal,
            'idCategoria' => $params->idCategoria,
            //'idSubLocal' => $params->idSubLocal,
            'idNatureza' => $params->idNatureza,
        ]);

        $query->andFilterWhere(['like', 'periodo', $params->periodo]);
       //     ->andFilterWhere(['like', 'detalheLocal', $params->detalheLocal])
         //   ->andFilterWhere(['like', 'descricao', $params->descricao])
          //  ->andFilterWhere(['like', 'procedimento', $params->procedimento])
           // ->andFilterWhere(['like', 'cpfUsuario', $params->cpfUsuario]);

    
        $params->idCategoria = $idCategoriabkp;
        $params->idNatureza = $idNaturezabkp;
        $params->periodo = $periodobkp;
        $params->dataInicial = $databkp;
        return $dataProvider;

    }*/
}
