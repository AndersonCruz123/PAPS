<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */

$this->title = 'Update Ocorrencia: ' . ' ' . $model->idOcorrencia;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idOcorrencia, 'url' => ['view', 'id' => $model->idOcorrencia]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ocorrencia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
