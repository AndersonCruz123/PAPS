<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */

$this->title = 'Nova Ocorrencia';
$this->params['breadcrumbs'][] = ['label' => 'Ocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
