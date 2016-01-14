<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Não encontramos o CPF informado';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title.': '.$cpf.'. Tente novamente com o CPF sem pontos e dígitos (., -)') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

</div>
