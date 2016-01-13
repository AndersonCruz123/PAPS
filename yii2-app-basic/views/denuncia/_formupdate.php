<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\LocalSearch;
use app\models\SublocalSearch;
use app\models\Sublocal;
use app\models\NaturezaocorrenciaSearch;
use app\models\CategoriaSearch;
/* @var $this yii\web\View */
/* @var $model app\models\Denuncia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="denuncia-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    $campos = '(*)Campos obrigatórios';
    ?>
    <h5 style="color:red;"><?= Html::encode($campos) ?></h5>
    
    <?php $arraystatus = [1 => 'Não verificada', 2=>'Verdadeira', 3=>'Falsa']; ?>
    <?php $arrayLocal = ArrayHelper::map( LocalSearch::find()->all(), 'idLocal', 'Nome'); ?>
    <?php $arrayNatureza = ArrayHelper::map( NaturezaocorrenciaSearch::find()->all(), 'idNatureza', 'Nome'); ?>
    <?php $arrayCategoria = ArrayHelper::map( CategoriaSearch::find()->all(), 'idCategoria', 'Nome'); ?>    
    <?php $arraySubLocal = ArrayHelper::map( SubLocal::find()->where(['idLocal' => $model->idLocal])->all(), 'idSubLocal', 'Nome'); ?>
    <?php $arrayPeriodo = [1=> 'Manhã', 2=>'Tarde', 3=>'Noite', 4=>'Madrugada']; ?>

    <?= $form->field($model, 'status')->dropdownlist($arraystatus, ['prompt'=>'Selecione o status da denúncia', 'style'=>'width:300px']) ?>
    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'idCategoria')->dropdownlist($arrayCategoria, ['prompt'=>'Selecione o Categoria da ocorrência', 'style'=>'width:300px']) ?>
    
    <?= $form->field($model, 'idNatureza')->dropdownlist($arrayNatureza, ['prompt'=>'Selecione a Natureza da ocorrência', 'style'=>'width:300px']) ?>
    <?php if ($model->idLocal == 0) {
    echo $form->field($model, 'idLocal')->dropDownList($arrayLocal,
             [
             'prompt' =>'Selecione o Local da Ocorrência' ,
              'onchange' =>'
                          console.log("carreguei a tela");
                    
                    $.get("index.php?r=sublocal/lists&id='.'" + $(this).val(), function(data){
                        $( "#denuncia-idsublocal").html(data);
                    });',         
                     'style'=>'width:300px'
             ]);
            
    echo $form->field($model, 'idSubLocal')->dropDownList(
                                    [
                                    'prompt' =>'Selecione o SubLocal da Ocorrência',
                    
                                    ]);
          } else {
    echo $form->field($model, 'idLocal')->dropDownList($arrayLocal,
             [
             'prompt' =>'Selecione o Local da Ocorrência' ,
              'onchange' =>'
                    $.get("index.php?r=sublocal/lists&id='.'" + $(this).val(), function(data){
                        $( "#denuncia-idsublocal").html(data);
                    });
                    ',
                    'options'=>[$model->idLocal=>['Selected'=>true]],
                     'style'=>'width:300px'          

             ]);
               
  echo $form->field($model, 'idSubLocal')->dropDownList($arraySubLocal,
                                    [
                                    'prompt' =>'Selecione o SubLocal da Ocorrência',
                                    'options'=>[$model->idSubLocalbkp=>['Selected'=>true]],
                                    'style'=>'width:300px'
                                    ]);  

    }
  ?>

    <?= $form->field($model, 'detalheLocal')->textInput(['maxlength' => true, 'style'=>'width:300px']) ?>

    <?= $form->field($model, 'data')->widget(
            DatePicker::className(), [
                // inline too, not bad
                 'inline' => false, 
                 'language' => 'pt',
                 // modify template for custom rendering
               // 'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true
                ],
        ]); ?>
        
    <?= $form->field($model, 'hora')->textInput(['maxlength' => true, 'style'=>'width:60px'])->hint('Exemplo: 12:30') ?>
    <?= $form->field($model, 'periodo')->dropdownlist($arrayPeriodo, ['prompt'=>'Selecione o Período da ocorrência', 'style'=>'width:300px']) ?>

  <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
