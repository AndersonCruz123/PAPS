<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sublocal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sublocal-form">

    <?php $form = ActiveForm::begin(); 
	$campos = '(*)Campos obrigatórios';
    ?>
    <h5 style="color:red;"><?= Html::encode($campos) ?></h5>
    <?= $form->field($model, 'Nome')->textInput(['maxlength' => true, 'style'=>'width:370px']) ?>

      <?= $form->field($model, 'longitude')->textInput(['maxlength' => true, 'style'=>'width:370px']) ?>
      
   	<?= $form->field($model, 'latitude')->textInput(['maxlength' => true, 'style'=>'width:370px']) ?>

    <?= $form->field($model, 'idLocal')->dropdownlist($arraylocal, ['prompt'=>'Selecione o local a que pertence este sublocal', 'style'=>'width:370px']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
