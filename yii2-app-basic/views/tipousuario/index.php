<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipousuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos de Usuário';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipousuario-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Tipo de Usuário', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'funcao',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
