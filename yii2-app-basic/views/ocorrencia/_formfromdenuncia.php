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
use app\models\Sublocal;
use app\models\CategoriaSearch;
use dosamigos\datepicker\DatePicker;
use kartik\timepicker\TimePicker;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ocorrencia-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    $campos = '(*)Campos obrigatórios';
    $msg = 'Obs.: Se a denúncia possui fotos, as fotos serão automaticamente salvas nesta ocorrência';
    ?>
    <h5 style="color:red;"><?= Html::encode($campos) ?></h5>    
    <h5 style="color:red;"><?= Html::encode($msg) ?></h5>

    <?php $arrayNatureza = ArrayHelper::map( NaturezaocorrenciaSearch::find()->all(), 'idNatureza', 'Nome'); ?>

    <?php $arrayLocal = ArrayHelper::map( LocalSearch::find()->all(), 'idLocal', 'Nome'); ?>

    <?php $arraySubLocal = ArrayHelper::map( SubLocal::find()->where(['idLocal' => $model->idLocal])->all(), 'idSubLocal', 'Nome'); ?>

    <?php $arrayCategoria = ArrayHelper::map( CategoriaSearch::find()->all(), 'idCategoria', 'Nome'); ?>

    <?php $arraystatus = [1 => 'Aberto', 2=>'Solucionado', 3=>'Não Solucionado']; ?>

    <?= $form->field($model, 'status')->dropdownlist($arraystatus, ['prompt'=>'Selecione o status da ocorrência', 'style'=>'width:300px']) ?>

    <?= $form->field($model, 'idCategoria')->dropdownlist($arrayCategoria, ['prompt'=>'Selecione o Categoria da ocorrência', 'style'=>'width:300px']) ?>
    
    <?= $form->field($model, 'idNatureza')->dropdownlist($arrayNatureza, ['prompt'=>'Selecione a Natureza da ocorrência', 'style'=>'width:300px']) ?>

<?php if ($model->idLocal == 0) {
    echo $form->field($model, 'idLocal')->dropDownList($arrayLocal,
             [
             'prompt' =>'Selecione o Local da Ocorrência' ,
              'onchange' =>'
                 					console.log("carreguei a tela");
                    
                    $.get("index.php?r=sublocal/lists&id='.'" + $(this).val(), function(data){
                        $( "#ocorrencia-idsublocal").html(data);
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
                        $( "#ocorrencia-idsublocal").html(data);
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
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true
                ]
        ]); ?>

    <?= $form->field($model, 'dataConclusao')->widget(
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

 
    <?= $form->field($model, 'hora')->textInput(['maxlength' => true])->widget(MaskedInput::className(), [
                    'mask' => '99:99',
                ]) ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'procedimento')->textarea(['rows' => 6]) ?>
    
  <?= $form->field($model, 'comentarioFoto')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Enviar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
