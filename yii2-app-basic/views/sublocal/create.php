<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sublocal */

$this->title = 'Create Sublocal';
$this->params['breadcrumbs'][] = ['label' => 'Sublocals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sublocal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'arraylocal' => $arraylocal
    ]) ?>

</div>
