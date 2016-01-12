<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipousuarioSearch;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin();
    $campos = '(*)Campos obrigatórios';
    ?>
    <h5 style="color:red;"><?= Html::encode($campos) ?></h5>

	<?php $arraytiposusuario=ArrayHelper::map(TipousuarioSearch::find()->all(),'idTipo','funcao'); ?>
    
    <?= $form->field($model, 'cpf')->textInput(['maxlength' => true, 'style'=>'width:180px'])->hint('Sem pontos e dígitos (., -)') ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'style'=>'width:500px']) ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'style'=>'width:500px']) ?>

    <?= $form->field($model, 'senha')->textInput()->passwordInput(['maxlength' => true, 'style'=>'width:250px']) ?>

       <?= $form->field($model, 'confirmarSenha')->textInput()->passwordInput(['maxlength' => true, 'style'=>'width:250px']) ?>

    <?= $form->field($model, 'idTipoUsuario')->dropdownlist($arraytiposusuario, ['prompt'=>'Selecione a função do usuário', 'style'=>'width:250px']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
