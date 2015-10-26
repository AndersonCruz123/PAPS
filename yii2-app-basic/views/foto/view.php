<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Foto */

$this->title = $model->idFoto;
$this->params['breadcrumbs'][] = ['label' => 'Fotos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idFoto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idFoto], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idFoto',
            'comentario',
            'idOcorrencia',
            'idDenuncia',
            'endereco',
        ],
    ]) ?>

</div>
