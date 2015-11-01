<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Local */

$this->title = $model->Nome;
$this->params['breadcrumbs'][] = ['label' => 'Locals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="local-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->idLocal], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->idLocal], [
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
            'idLocal',
            'Nome',
        ],
    ]) ?>

</div>
