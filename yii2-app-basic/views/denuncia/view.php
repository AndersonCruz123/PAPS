<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Denuncia */

$this->title = $model->idDenuncia;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="denuncia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->idDenuncia], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->idDenuncia], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Você tem certeza que deseja deletar este item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Gerar PDF da denúncia', ['printdenuncia', 'id' => $model->idDenuncia], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idDenuncia',
            'descricao:ntext',
            'idLocal',
            'idSubLocal',
            'data',
            'hora',
            'periodo',
            'status',
        ],
    ]) ?>

</div>
