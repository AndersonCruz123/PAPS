<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */

$this->title = $model->idOcorrencia;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Salvar', ['update', 'id' => $model->idOcorrencia], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->idOcorrencia], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Você tem certeza que deseja deletar este item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idOcorrencia',
            'status',
            'data',
            'hora',
            'periodo',
            'detalheLocal',
            'descricao:ntext',
            'procedimento:ntext',
            'dataConclusao',
            'idCategoria',
            'idSubLocal',
            'idNatureza',
            'cpfUsuario',
        ],
    ]) ?>

</div>
