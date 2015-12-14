<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */

$this->title = 'Gerar Relatório';
$this->params['breadcrumbs'][] = ['label' => 'Gerar Relatório', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_relatorio', [
        'model' => $model,
    ]) ?>

</div>
