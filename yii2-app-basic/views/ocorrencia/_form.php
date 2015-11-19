<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\NaturezaocorrenciaSearch;
use app\models\LocalSearch;
use app\models\SubLocalSearch;
use app\models\CategoriaSearch;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ocorrencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $arrayNatureza = ArrayHelper::map( NaturezaocorrenciaSearch::find()->all(), 'idNatureza', 'Nome'); ?>

    <?php $arrayLocal = ArrayHelper::map( LocalSearch::find()->all(), 'idLocal', 'Nome'); ?>

    <?php $arrayCategoria = ArrayHelper::map( CategoriaSearch::find()->all(), 'idCategoria', 'Nome'); ?>

    <?php $arraystatus = ['Verificado', 'Não Verificado']; ?>

    <?php $arrayPeriodo = ['Matutino', 'Vespertino', 'Noturno']; ?>

    <?= $form->field($model, 'status')->dropdownlist($arraystatus, ['prompt'=>'Selecione o status da ocorrência']) ?>

    <?= $form->field($model, 'data')->widget(
            DatePicker::className(), [
                // inline too, not bad
                 'inline' => false, 
                 // modify template for custom rendering
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-M-yyyy'
                ]
        ]); ?>

    <?= $form->field($model, 'hora')->textInput()->hint('Exemplo: 12:30') ?>

    <?= $form->field($model, 'periodo')->dropdownlist($arrayPeriodo, ['prompt'=>'Selecione o Período da ocorrência']) ?>

    <?= $form->field($model, 'detalheLocal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'procedimento')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'dataConclusao')->widget(
            DatePicker::className(), [
                // inline too, not bad
                 'inline' => false, 
                 // modify template for custom rendering
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-M-yyyy'
                ]
        ]); ?>

    <?= $form->field($model, 'idCategoria')->dropdownlist($arrayCategoria, ['prompt'=>'Selecione o Categoria da ocorrência']) ?>

    <?= $form->field($model, 'idLocal')->dropDownList($arrayLocal,
             [
             'prompt' =>'Selecione o Local da Ocorrência' ,
              'onchange' =>'                    
                    $.post("index.php?r=sublocal/lists&id='.'" + $(this).val(), function(data){
                        $( "select#ocorrencia-idsublocal").html(data);
                    });'
             ]); ?>

    <?= $form->field($model, 'idSubLocal')->dropDownList(
                                    ArrayHelper::map(SubLocalSearch::find()->all(),'idSubLocal','Nome') ,
                                    [
                                    'prompt' =>'Selecione o SubLocal da Ocorrência'
                                    ]); ?>

    <?= $form->field($model, 'idNatureza')->dropdownlist($arrayNatureza, ['prompt'=>'Selecione a Natureza da ocorrência']) ?>

    <?= $form->field($model, 'cpfUsuario')->dropdownlist(['value' => Yii::$app->user->identity->cpf]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Enviar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
