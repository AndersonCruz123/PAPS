<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DenunciaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="denuncia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idDenuncia') ?>

    <?= $form->field($model, 'descricao') ?>

    <?= $form->field($model, 'idLocal') ?>

    <?= $form->field($model, 'data') ?>

    <?= $form->field($model, 'hora') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
