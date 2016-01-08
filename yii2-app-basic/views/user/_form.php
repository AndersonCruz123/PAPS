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
    
    <?= $form->field($model, 'cpf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'senha')->textInput(['maxlength' => true]) ?>

       <?= $form->field($model, 'confirmarSenha')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idTipoUsuario')->dropdownlist($arraytiposusuario, ['prompt'=>'Selecione a função do usuário']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
