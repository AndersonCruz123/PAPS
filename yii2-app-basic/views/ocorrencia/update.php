<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */

$this->title = 'Editar Ocorrencia de número: ' . ' ' . $model->idOcorrencia;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idOcorrencia, 'url' => ['view', 'id' => $model->idOcorrencia]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="ocorrencia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
