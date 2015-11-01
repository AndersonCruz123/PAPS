<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Naturezaocorrencia */

$this->title = 'Editar Natureza: ' . ' ' . $model->Nome;
$this->params['breadcrumbs'][] = ['label' => 'Naturezaocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idNatureza, 'url' => ['view', 'id' => $model->idNatureza]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="naturezaocorrencia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
