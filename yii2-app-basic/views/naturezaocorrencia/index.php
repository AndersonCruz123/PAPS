<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NaturezaocorrenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Natureza das ocorrencias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="naturezaocorrencia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nova Natureza', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idNatureza',
            'Nome',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
