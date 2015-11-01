<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tipousuario */

$this->title = 'Editar Tipo de usuário: ' . ' ' . $model->idTipo;
$this->params['breadcrumbs'][] = ['label' => 'Tipousuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idTipo, 'url' => ['view', 'id' => $model->idTipo]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="tipousuario-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
