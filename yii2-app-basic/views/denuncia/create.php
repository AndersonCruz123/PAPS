<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Denuncia */

$this->title = 'Faça sua Denuncia';
$this->params['breadcrumbs'][] = ['label' => 'Denuncias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="denuncia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
