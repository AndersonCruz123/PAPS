<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DenunciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Denuncias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="denuncia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Denuncia', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idDenuncia',
           'status',
            'descricao:ntext',
            'local',
            'data',
            'hora',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
