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
use app\models\SublocalSearch;
use app\models\CategoriaSearch;
use dosamigos\datepicker\DatePicker;
use kartik\timepicker\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ocorrencia-form">
   
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


   <?=$form->field($model,'radiobutton')->radioList([1 => 'Por mês e ano', 2 => 'Por data inicial e data final' ],[
              'onchange' =>'
                        console.log("checkbox1");
 
                           var radios = document.getElementsByName("Relatorio[radiobutton]");
                        
                        if (radios[1].checked == false) {
                            $( "#relatorio-mes").prop("disabled", true);
                            $( "#relatorio-ano").prop("disabled", true);   
                            $( "#relatorio-datafinal").prop("disabled", false);
                            $( "#relatorio-datainicial").prop("disabled", false);                                                                     
                        } else {

                           $( "#relatorio-mes").prop("disabled", false);
                            $( "#relatorio-ano").prop("disabled", false);                                                                        
                            $( "#relatorio-datafinal").prop("disabled", true);
                            $( "#relatorio-datainicial").prop("disabled", true);
                        }
 
                         console.log(radios[0].checked);
                                ']

   ); ?>


    <?php $arrayMes = [1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',9 => 'Setembro',10 => 'Outubro',11 => 'Novembro',12 => 'Dezembro']; ?>
    <?= $form->field($model, 'mes')->dropdownlist($arrayMes, ['prompt'=>'Selecione o mês']) ?>

    <?php $arrayAno = [2014 => '2014', 2015 => '2015', 2016 => '2016']?>
    <?= $form->field($model, 'ano')->dropdownlist($arrayAno, ['prompt'=>'Selecione o ano']) ?>


    <?= $form->field($model, 'dataInicial')->widget(
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

    <?= $form->field($model, 'dataFinal')->widget(
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

    <?php $arrayNatureza = ArrayHelper::map( NaturezaocorrenciaSearch::find()->all(), 'idNatureza', 'Nome'); 
          $arrayNatureza[0] = 'Todos';
    ?>

    <?php $arrayLocal = ArrayHelper::map( LocalSearch::find()->all(), 'idLocal', 'Nome'); 
          $arrayLocal[0] = 'Todos';
    ?>

    <?php 
    $arrayCategoria = ArrayHelper::map( CategoriaSearch::find()->all(), 'idCategoria', 'Nome'); 
    $arrayCategoria[0] = 'Todos';
    ?>

    <?php $arraystatus = [0 => 'Todos', 1 => 'Aberto', 2=>'Solucionado', 3=>'Não Solucionado']; ?>

    <?php $arrayPeriodo = [0 => 'Todos', 1=> 'Manhã', 2=>'Tarde', 3=>'Noite', 4=>'Madrugada']; ?>


    <?= $form->field($model, 'status')->dropdownlist($arraystatus, ['options'=>[0=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'idCategoria')->dropdownlist($arrayCategoria, ['options'=>[0=>['Selected'=>true]]]) ?>
    
    <?= $form->field($model, 'idNatureza')->dropdownlist($arrayNatureza, ['options'=>[0=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'idLocal')->dropdownlist($arrayLocal, ['options'=>[0=>['Selected'=>true]]]) ?>
  
      <?= $form->field($model, 'periodo')->dropdownlist($arrayPeriodo, ['options'=>[0=>['Selected'=>true]]]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Enviar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

   <?=  "<script>
    var datafinal = document.getElementById('relatorio-datafinal');
    datafinal.disabled = true;

    var datainicial = document.getElementById('relatorio-datainicial');
    datainicial.disabled = true;

    var mes = document.getElementById('relatorio-mes');
    mes.disabled=true;
 
    var ano = document.getElementById('relatorio-ano');
    ano.disabled=true;
  </script>"

  ?>
