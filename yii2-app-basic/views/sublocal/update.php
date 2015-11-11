<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sublocal */

$this->title = 'Editar: ' . ' ' . $model->Nome;
$this->params['breadcrumbs'][] = ['label' => 'Sublocais', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Nome, 'url' => ['view', 'id' => $model->idSubLocal]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="sublocal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'arraylocal' => $arraylocal
    ]) ?>

</div>
