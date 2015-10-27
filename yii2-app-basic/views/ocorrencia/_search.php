<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OcorrenciaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ocorrencia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idOcorrencia') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'data') ?>

    <?= $form->field($model, 'hora') ?>

    <?= $form->field($model, 'periodo') ?>

    <?php // echo $form->field($model, 'detalheLocal') ?>

    <?php // echo $form->field($model, 'descricao') ?>

    <?php // echo $form->field($model, 'procedimento') ?>

    <?php // echo $form->field($model, 'dataConclusao') ?>

    <?php // echo $form->field($model, 'idCategoria') ?>

    <?php // echo $form->field($model, 'idSubLocal') ?>

    <?php // echo $form->field($model, 'idNatureza') ?>

    <?php // echo $form->field($model, 'cpfUsuario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
