<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FotoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="foto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idFoto') ?>

    <?= $form->field($model, 'comentario') ?>

    <?= $form->field($model, 'idOcorrencia') ?>

    <?= $form->field($model, 'idDenuncia') ?>

    <?= $form->field($model, 'endereco') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
