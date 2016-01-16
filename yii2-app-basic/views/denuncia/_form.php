<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\LocalSearch;
use app\models\SublocalSearch;
use app\models\Sublocal;
use yii\widgets\MaskedInput;
/* @var $this yii\web\View */
/* @var $model app\models\Denuncia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="denuncia-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    $campos = '(*)Campos obrigatórios';
    ?>
    <h5 style="color:red;"><?= Html::encode($campos) ?></h5>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?php $arrayLocal = ArrayHelper::map( LocalSearch::find()->all(), 'idLocal', 'Nome'); ?>
    <?php $arraySubLocal = ArrayHelper::map( SubLocal::find()->where(['idLocal' => $model->idLocal])->all(), 'idSubLocal', 'Nome'); ?>
    <?php $arrayPeriodo = [1=> 'Manhã', 2=>'Tarde', 3=>'Noite', 4=>'Madrugada']; ?>

<?php if ($model->idLocal == 0) {
    echo $form->field($model, 'idLocal')->dropDownList($arrayLocal,
             [
             'prompt' =>'Selecione o Local da Denúncia' ,
              'onchange' =>'
                          console.log("carreguei a tela");
                    
                    $.get("index.php?r=sublocal/lists&id='.'" + $(this).val(), function(data){
                        $( "#denuncia-idsublocal").html(data);
                    });',         
                     'style'=>'width:300px'
             ]);
            
    echo $form->field($model, 'idSubLocal')->dropDownList(
                                    [
                                    'prompt' =>'Selecione o SubLocal da Denúncia',
                    
                                    ]);
          } else {
    echo $form->field($model, 'idLocal')->dropDownList($arrayLocal,
             [
             'prompt' =>'Selecione o Local da Denúncia' ,
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
                                    'prompt' =>'Selecione o SubLocal da Denúncia',
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
        
    <?= $form->field($model, 'hora')->textInput(['maxlength' => true])->widget(MaskedInput::className(), [
                    'mask' => '99:99',
                ]) ?>
    <?= $form->field($model, 'periodo')->dropdownlist($arrayPeriodo, ['prompt'=>'Selecione o Período da Denúncia', 'style'=>'width:300px']) ?>

  <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
  <?= $form->field($model, 'comentarioFoto')->textarea(['rows' => 3]) ?>
  
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
