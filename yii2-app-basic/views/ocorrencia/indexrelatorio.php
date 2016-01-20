<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OcorrenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ocorrências';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php
echo "
<script>
    
    function myGrafico(){
        window.open('index.php?r=ocorrencia%2Fprintgraficos&periodo=".$model->periodo."&idCategoria=".$model->idCategoria."&status=".$model->status."&idNatureza=".$model->idNatureza."&idLocal=".$model->idLocal."&dataInicial=".$model->dataInicial."&dataFinal=".$model->dataFinal."&radiobutton=".$model->radiobutton."','_blank','toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50');
    }

    function myRelatorio(){
        window.open('index.php?r=ocorrencia%2Fprintrelatorio&periodo=".$model->periodo."&idCategoria=".$model->idCategoria."&status=".$model->status."&idNatureza=".$model->idNatureza."&idLocal=".$model->idLocal."&dataInicial=".$model->dataInicial."&dataFinal=".$model->dataFinal."&radiobutton=".$model->radiobutton."','_blank','toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50');
    }

    function myGraficoStatus(){
        window.open('index.php?r=ocorrencia%2Fprintgrafico&tipo=2&periodo=".$model->periodo."&idCategoria=".$model->idCategoria."&status=".$model->status."&idNatureza=".$model->idNatureza."&idLocal=".$model->idLocal."&dataInicial=".$model->dataInicial."&dataFinal=".$model->dataFinal."&radiobutton=".$model->radiobutton."','_blank','toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50');
    }

    function myGraficoPeriodo(){
        window.open('index.php?r=ocorrencia%2Fprintgrafico&tipo=1&periodo=".$model->periodo."&idCategoria=".$model->idCategoria."&status=".$model->status."&idNatureza=".$model->idNatureza."&idLocal=".$model->idLocal."&dataInicial=".$model->dataInicial."&dataFinal=".$model->dataFinal."&radiobutton=".$model->radiobutton."','_blank','toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50');
    }

    function myGraficoNatureza(){
        window.open('index.php?r=ocorrencia%2Fprintgrafico&tipo=3&periodo=".$model->periodo."&idCategoria=".$model->idCategoria."&status=".$model->status."&idNatureza=".$model->idNatureza."&idLocal=".$model->idLocal."&dataInicial=".$model->dataInicial."&dataFinal=".$model->dataFinal."&radiobutton=".$model->radiobutton."','_blank','toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50');
    }

    function myGraficoCategoria(){
        window.open('index.php?r=ocorrencia%2Fprintgrafico&tipo=4&periodo=".$model->periodo."&idCategoria=".$model->idCategoria."&status=".$model->status."&idNatureza=".$model->idNatureza."&idLocal=".$model->idLocal."&dataInicial=".$model->dataInicial."&dataFinal=".$model->dataFinal."&radiobutton=".$model->radiobutton."','_blank','toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50');
    }

    function myGraficoLocal(){
        window.open('index.php?r=ocorrencia%2Fprintgrafico&tipo=5&&periodo=".$model->periodo."&idCategoria=".$model->idCategoria."&status=".$model->status."&idNatureza=".$model->idNatureza."&idLocal=".$model->idLocal."&dataInicial=".$model->dataInicial."&dataFinal=".$model->dataFinal."&radiobutton=".$model->radiobutton."','_blank','toolbar=no, location=yes, directories=no, status=no, scrollbars=yes, resizable=yes, width=800, height=600, top=30, left=50');
    }    
</script>";
?>
<?php
 $html="  <p>

  <button class='btn btn-success' onclick='myRelatorio()'>Gerar PDF do relatório</button>";

    if($model->status==0)$html.="
    <button class='btn btn-danger' onclick='myGraficoStatus()''>Gráfico por Status</button>";

    if($model->periodo==0)$html.="
    <button class='btn btn-danger' onclick='myGraficoPeriodo()''>Gráfico por Período</button>";

    if($model->idNatureza==0)$html.="    
    <button class='btn btn-danger' onclick='myGraficoNatureza()'>Gráfico por Natureza</button>";
    
        if($model->idCategoria==0)$html.="
    <button class='btn btn-danger' onclick='myGraficoCategoria()'>Gráfico por Categoria</button>";

    if($model->idLocal==0)$html.="
    <button class='btn btn-danger' onclick='myGraficoLocal()''>Gráfico por Local</button>";
    
    $html.="
    </p>";

    echo $html;
?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      // 'filterModel' => $searchModel,
        'summary' => "Exibindo {begin} - {end} de {totalCount} items",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           ['attribute'=>'idOcorrencia',
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'status',
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'data',
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'hora',            
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'periodo',
             'contentOptions'=>['style'=>'width: 10px;']],
            // 'detalheLocal',
            // 'descricao:ntext',
            // 'procedimento:ntext',
            // 'dataConclusao',
            ['attribute'=>'idCategoria',            
             'contentOptions'=>['style'=>'width: 10px;']],
            ['attribute'=>'idLocal',            
             'contentOptions'=>['style'=>'width: 140px;']],
            ['attribute'=>'idSubLocal',            
             'contentOptions'=>['style'=>'width: 140px;']],
            ['attribute'=>'idNatureza',            
             'contentOptions'=>['style'=>'width: 10px;']],
            // 'cpfUsuario',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
