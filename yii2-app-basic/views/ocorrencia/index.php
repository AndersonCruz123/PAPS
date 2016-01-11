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
        <?= Html::a('Nova Ocorrência', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'idOcorrencia',
            'status',
            'data',
            //'hora',
            'periodo',
            'idLocal',
            // 'detalheLocal',
            // 'descricao:ntext',
            // 'procedimento:ntext',
            // 'dataConclusao',
             'idCategoria',
         //    'idLocal',
             'idNatureza',
            // 'cpfUsuario',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
