<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Naturezaocorrencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="naturezaocorrencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Nome')->textInput(['maxlength' => true, 'style'=>'width:500px']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
