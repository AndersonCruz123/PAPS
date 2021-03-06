<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DenunciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Denúncias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="denuncia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => "Exibindo {begin} - {end} de {totalCount} items",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute'=>'idDenuncia',
             'contentOptions'=>['style'=>'width: 10px;']],
           ['attribute'=>'status',
             'contentOptions'=>['style'=>'width: 115px;']],           
        //    'descricao:ntext',
         //   'idSubLocal',
            ['attribute'=>'hora',            
             'contentOptions'=>['style'=>'width: 90px;']],
            ['attribute'=>'idLocal',            
             'contentOptions'=>['style'=>'width: 130px;']],
            ['attribute'=>'idSubLocal',            
             'contentOptions'=>['style'=>'width: 130px;']],
            ['attribute'=>'data',
             'contentOptions'=>['style'=>'width: 50px;']],
            ['attribute'=> 'periodo',
             'contentOptions'=>['style'=>'width: 90px;']],            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
