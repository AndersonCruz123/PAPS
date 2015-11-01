<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Local */

$this->title = 'Novo Local';
$this->params['breadcrumbs'][] = ['label' => 'Locals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="local-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
