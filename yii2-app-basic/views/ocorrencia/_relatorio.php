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


 
 <fieldset>
    <legend>Filtrar por data</legend>
   <?=$form->field($model,'radiobutton')->radioList([1 => 'Por mês e ano', 2 => 'Por data inicial e data final' ],[
              'onchange' =>'
                        console.log($( "#relatorio-mesano").val());
                         console.log($( "#relatorio-datainicial").val());
                           var radios = document.getElementsByName("Relatorio[radiobutton]");
                        
                        var divdatas = document.getElementById("divdatas");
                        var divmesano = document.getElementById("divmesano");

                        if (radios[1].checked == false) {

                          divmesano.style.display = "none";
                          divdatas.style.display = "block";
                                                                 
                        } else {

                           divdatas.style.display = "none";
                           divmesano.style.display = "block";
                           
                        }
 
                         console.log(radios[0].checked);
                                ']

   ); ?>

   <div id="divmesano">
    <?= $form->field($model, 'mesAno')->widget(
            DatePicker::className(), [
                // inline too, not bad
                 'inline' => false, 
                 'language' => 'pt',
                 // modify template for custom rendering
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'M-yyyy',
   					'minViewMode' =>1,


                       //'' => 'M-yyyy',

                ]
        ]); ?>
</div>

<div id="divdatas">
    <?= $form->field($model, 'dataInicial')->widget(
            DatePicker::className(), [
                // inline too, not bad
                 'inline' => false, 
                 'language' => 'pt',
                 // modify template for custom rendering
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy',
                   'todayHighlight' => true

                ]
        ]); ?>

    <?= $form->field($model, 'dataFinal')->widget(
            DatePicker::className(), [
                // inline too, not bad
                 'inline' => false, 
                 'language' => 'pt',
                 // modify template for custom rendering
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                     'todayBtn' => true              
                ]
        ]); ?>
</div>
</fieldset>


 <fieldset>
    <legend>Filtrar por</legend>

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


    <?= $form->field($model, 'status')->dropdownlist($arraystatus, ['options'=>[0=>['Selected'=>true]], 'style'=>'width:300px']) ?>

    <?= $form->field($model, 'idCategoria')->dropdownlist($arrayCategoria, ['options'=>[0=>['Selected'=>true]], 'style'=>'width:300px']) ?>
    
    <?= $form->field($model, 'idNatureza')->dropdownlist($arrayNatureza, ['options'=>[0=>['Selected'=>true]], 'style'=>'width:300px']) ?>

    <?= $form->field($model, 'idLocal')->dropdownlist($arrayLocal, ['options'=>[0=>['Selected'=>true]], 'style'=>'width:300px']) ?>
  
      <?= $form->field($model, 'periodo')->dropdownlist($arrayPeriodo, ['options'=>[0=>['Selected'=>true]], 'style'=>'width:300px']) ?>
</fieldset>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Enviar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

  <?=  "<script>

    var radios = document.getElementsByName('Relatorio[radiobutton]');
    var divdatas = document.getElementById('divdatas');
    var divmesano = document.getElementById('divmesano');

    if (radios[1].checked == true) {
    	divdatas.style.display = 'none';    	
    }

    else if (radios[2].checked == true) {
  		divmesano.style.display = 'none';
    } else {
    	radios[1].checked = false;
    	radios[2].checked = false;
    	
    	divdatas.style.display = 'none';
  		divmesano.style.display = 'none';
  	}
  </script>"

  ?> 
