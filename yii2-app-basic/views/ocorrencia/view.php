<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Foto;
/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencia */

$this->title = $model->idOcorrencia;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ocorrencia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->idOcorrencia], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->idOcorrencia], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Você tem certeza que deseja deletar este item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Gerar PDF da ocorrência', ['printocorrencia', 'id' => $model->idOcorrencia], ['class' => 'btn btn-success']) ?>
    </p>

    <?php $tam = sizeof($model->fotos); ?>

    <?php if ($tam == 0) {
   echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idOcorrencia',
            'status',
            'data',
            'hora',
            'periodo',
            'detalheLocal',
            'descricao:ntext',
            'procedimento:ntext',
            'dataConclusao',
            'idCategoria',
            'idLocal',
            'idSubLocal',
            'idNatureza',
            'cpfUsuario',
        ],
    ]);} 

    elseif ($tam == 1) {
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idOcorrencia',
            'status',
            'data',
            'hora',
            'periodo',
            'detalheLocal',
            'descricao:ntext',
            'procedimento:ntext',
            'dataConclusao',
            'idCategoria',
            'idLocal',
            'idSubLocal',
            'idNatureza',
            'cpfUsuario',
         //   'fotos',
            'comentarioFoto',
            [                      // the owner name of the model
            'label' => 'foto 1',
            'value' => $model->fotos[0]->endereco,
            ]
        ],
    ]);}

    elseif ($tam == 2) {
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idOcorrencia',
            'status',
            'data',
            'hora',
            'periodo',
            'detalheLocal',
            'descricao:ntext',
            'procedimento:ntext',
            'dataConclusao',
            'idCategoria',
            'idLocal',
            'idSubLocal',
            'idNatureza',
            'cpfUsuario',
         //   'fotos',
            'comentarioFoto',
            [                      // the owner name of the model
            'label' => 'foto 1',
            'value' => $model->fotos[0]->nome,
            ],
            [                      // the owner name of the model
            'label' => 'foto 2',
            'value' => $model->fotos[1]->nome,
            ],
        ],
    ]);}

    elseif ($tam == 3) {
   echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idOcorrencia',
            'status',
            'data',
            'hora',
            'periodo',
            'detalheLocal',
            'descricao:ntext',
            'procedimento:ntext',
            'dataConclusao',
            'idCategoria',
            'idLocal',
            'idSubLocal',
            'idNatureza',
            'cpfUsuario',
         //   'fotos',
            'comentarioFoto',
            [                      // the owner name of the model
            'label' => 'foto 1',
            'value' => $model->fotos[0]->nome,
            ],

            [                      // the owner name of the model
            'label' => 'foto 2',
            'value' => $model->fotos[1]->nome,
            ],           
            [                      // the owner name of the model
            'label' => 'foto 3',
            'value' => $model->fotos[2]->nome,
            ]

        ],
    ]);}

    elseif ($tam == 4) {
    echo    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idOcorrencia',
            'status',
            'data',
            'hora',
            'periodo',
            'detalheLocal',
            'descricao:ntext',
            'procedimento:ntext',
            'dataConclusao',
            'idCategoria',
            'idLocal',
            'idSubLocal',
            'idNatureza',
            'cpfUsuario',
         //   'fotos',
            'comentarioFoto',
            [                      // the owner name of the model
            'label' => 'foto 1',
            'value' => $model->fotos[0]->nome,
            ],
            [                      // the owner name of the model
            'label' => 'foto 2',
            'value' => $model->fotos[1]->nome,
            ],
            [                      // the owner name of the model
            'label' => 'foto 3',
            'value' => $model->fotos[2]->nome,
            ],
            [                      // the owner name of the model
            'label' => 'foto 4',
            'value' => $model->fotos[3]->nome,
            ]           
        ],
    ]);
    }
    ?>



</div>
