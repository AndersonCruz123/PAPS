<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sublocal */

$this->title = $model->Nome;
$this->params['breadcrumbs'][] = ['label' => 'Sublocais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sublocal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->idSubLocal], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->idSubLocal], [
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
            'Nome',
            'idLocal',
            'longitude',
            'latitude'
        ],
    ]) ?>

</div>
