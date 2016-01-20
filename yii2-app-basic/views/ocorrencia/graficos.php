<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OcorrenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gráficos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


</div>
<?php

 $html = "<html>
  <head>
    <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
    <script type='text/javascript'>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {";
if ($arrayPeriodo[0]!=-1) {
  $html=$html."     

        var dataPeriodo = google.visualization.arrayToDataTable([
          ['Periodo', 'Quantidade'],
          ['Manhã',     ".$arrayPeriodo[0]."],
          ['Tarde',      ".$arrayPeriodo[1]."],
          ['Noite',  ".$arrayPeriodo[2]."],
          ['Madrugada', ".$arrayPeriodo[3]."]
        ]);

        var optionsPeriodo = {
          title: 'Ocorrências por Periodo'
        };

        var chartPeriodo = new google.visualization.PieChart(document.getElementById('periodo'));

        chartPeriodo.draw(dataPeriodo, optionsPeriodo);"
    ;
      }


if ($arrayStatus[0]!=-1) {
$html = $html."

        var dataStatus = google.visualization.arrayToDataTable([
          ['Status', 'Quantidade'],
          ['Aberta',     ".$arrayStatus[0]."],
          ['Solucionada',      ".$arrayStatus[1]."],
          ['Não Solucionada',  ".$arrayStatus[2]."],
        ]);

        var optionsStatus = {
          title: 'Ocorrências por Status'
        };

        var chartStatus = new google.visualization.PieChart(document.getElementById('status'));

        chartStatus.draw(dataStatus, optionsStatus);";
}

   if($arrayNatureza['quantidade'][1] != -1) {
$html = $html."

        var dataNatureza = google.visualization.arrayToDataTable([
          ['Natureza', 'Quantidade'],";

    for($i = 0; $i < sizeof($arrayNatureza['quantidade']);$i++)
    {
        $html.="['".$arrayNatureza['nome'][$i]."',".$arrayNatureza['quantidade'][$i]."],";
    }
       $html.=" ]);

        var optionsNatureza = {
          title: 'Ocorrências por Natureza'
        };

        var chartNatureza = new google.visualization.ColumnChart(document.getElementById('natureza'));

        chartNatureza.draw(dataNatureza, optionsNatureza);";
}
   if($arrayCategoria['quantidade'][1] != -1) {
        $html = $html."

        var dataCategoria = google.visualization.arrayToDataTable([
          ['Categoria', 'Quantidade'],";

    for($i = 0; $i < sizeof($arrayCategoria['quantidade']);$i++)
    {
        $html.="['".$arrayCategoria['nome'][$i]."',".$arrayCategoria['quantidade'][$i]."],";
    }
       $html.=" ]);

        var optionsCategoria = {
          title: 'Ocorrências por Categoria'
        };

        var chartCategoria = new google.visualization.ColumnChart(document.getElementById('categoria'));

        chartCategoria.draw(dataCategoria, optionsCategoria);";
}

   if($arrayLocal['quantidade'][1] != -1) {
        $html = $html."

        var dataLocal = google.visualization.arrayToDataTable([
          ['Local', 'Quantidade'],";

    for($i = 0; $i < sizeof($arrayLocal['quantidade']);$i++)
    {
        $html.="['".$arrayLocal['nome'][$i]."',".$arrayLocal['quantidade'][$i]."],";
    }
       $html.=" ]);

        var optionsLocal = {
          title: 'Ocorrências por Local'
        };

        var chartLocal = new google.visualization.ColumnChart(document.getElementById('local'));

        chartLocal.draw(dataLocal, optionsLocal);";
    }
//}

$html = $html."}
    </script>
  </head>
  <body>";
   if($arrayStatus[0]!=-1) $html.="<div id='status' style='width: 900px; height: 500px;'></div>";
   if($arrayPeriodo[0]!=-1) $html.= "<div id='periodo' style='width: 900px; height: 500px;'></div>";
   if($arrayNatureza['quantidade'][1] != -1)
  $html.="<div id='natureza' style='width: 900px; height: 500px;'></div>";
   if($arrayCategoria['quantidade'][1] != -1)
    $html.="<div id='categoria' style='width: 900px; height: 500px;'></div>";
   if($arrayLocal['quantidade'][1] != -1)
    $html.="<div id='local' style='width: 900px; height: 500px;'></div>";

$html = $html."
    </body>
    </html>
    ";

echo $html;
?>