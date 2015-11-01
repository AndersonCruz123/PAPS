<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Naturezaocorrencia */

$this->title = 'Nova Natureza';
$this->params['breadcrumbs'][] = ['label' => 'Naturezaocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="naturezaocorrencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
