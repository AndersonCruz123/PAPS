<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SublocalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sublocais';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sublocal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Sublocal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idSubLocal',
            'Nome',
            'idLocal',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
