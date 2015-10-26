<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sublocal */

$this->title = 'Update Sublocal: ' . ' ' . $model->idSubLocal;
$this->params['breadcrumbs'][] = ['label' => 'Sublocals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idSubLocal, 'url' => ['view', 'id' => $model->idSubLocal]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sublocal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
