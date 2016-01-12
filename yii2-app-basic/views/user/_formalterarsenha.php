<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Alterar senha';
$this->params['breadcrumbs'][] = ['label' => 'Alterar Senha', 'url' => ['alterarsenha']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'senha')->passwordInput(['maxlength' => true, 'style'=>'width:170px']) ?>

    <?= $form->field($model, 'confirmarSenha')->passwordInput(['maxlength' => true, 'style'=>'width:170px']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
