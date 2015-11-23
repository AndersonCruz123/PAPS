<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Denuncia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="denuncia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $arraystatus = [1 => 'Não verificada', 2=>'Verdadeira', 3=>'Falsa']; ?>

   <?= $form->field($model, 'status')->dropdownlist($arraystatus, ['prompt'=>'Selecione o status da denuncia']) ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data')->widget(
            DatePicker::className(), [
                // inline too, not bad
                 'inline' => false, 
                 'language' => 'pt',
                 // modify template for custom rendering
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
        ])->hint('Ano, mês, dia'); ?>
        
    <?= $form->field($model, 'hora')->textInput()->hint('Exemplo: 12:30') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
