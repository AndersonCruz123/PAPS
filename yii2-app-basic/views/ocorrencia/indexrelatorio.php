<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OcorrenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ocorrências';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Gerar PDF do relatório', ['printrelatorio', 'periodo' => $model->periodo, 'idCategoria' => $model->idCategoria,
                                  'status'=>$model->status, 'idNatureza' =>$model->idNatureza, 'idLocal' =>$model->idLocal, 
                                  'dataInicial' =>$model->dataInicial, 'dataFinal' => $model->dataFinal, 
                                  'radiobutton' => $model->radiobutton], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Gerar Gráficos', ['printgraficos', 'periodo' => $model->periodo, 'idCategoria' => $model->idCategoria,
                                  'status'=>$model->status, 'idNatureza' =>$model->idNatureza, 'idLocal' =>$model->idLocal, 
                                  'dataInicial' =>$model->dataInicial, 'dataFinal' => $model->dataFinal, 
                                  'radiobutton' => $model->radiobutton], ['class' => 'btn btn-danger']) ?>

    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      // 'filterModel' => $searchModel,
        'summary' => "Exibindo {begin} - {end} de {totalCount} items",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           ['attribute'=>'idOcorrencia',
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'status',
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'data',
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'hora',            
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'periodo',
             'contentOptions'=>['style'=>'width: 10px;']],
            // 'detalheLocal',
            // 'descricao:ntext',
            // 'procedimento:ntext',
            // 'dataConclusao',
            ['attribute'=>'idCategoria',            
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'idLocal',            
             'contentOptions'=>['style'=>'width: 140px;']],
            ['attribute'=>'idSubLocal',            
             'contentOptions'=>['style'=>'width: 140px;']],
            ['attribute'=>'idNatureza',            
             'contentOptions'=>['style'=>'width: 10px;']],
            // 'cpfUsuario',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
