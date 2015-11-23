<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Denuncia */

$this->title = 'Atualizar Denuncia de número: ' . ' ' . $model->idDenuncia;
$this->params['breadcrumbs'][] = ['label' => 'Denuncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idDenuncia, 'url' => ['view', 'id' => $model->idDenuncia]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="denuncia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
    ]) ?>

</div>
