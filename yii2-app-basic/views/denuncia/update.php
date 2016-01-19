<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Denuncia */

$this->title = 'Atualizar denúncia de número: ' . ' ' . $model->idDenuncia;
$this->params['breadcrumbs'][] = ['label' => 'Denúncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idDenuncia, 'url' => ['view', 'id' => $model->idDenuncia]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="denuncia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
    ]) ?>

</div>
