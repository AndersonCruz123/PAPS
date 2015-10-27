<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sublocal */

$this->title = $model->idSubLocal;
$this->params['breadcrumbs'][] = ['label' => 'Sublocals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sublocal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idSubLocal], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idSubLocal], [
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
            'idSubLocal',
            'Nome',
            'idLocal',
        ],
    ]) ?>

</div>
