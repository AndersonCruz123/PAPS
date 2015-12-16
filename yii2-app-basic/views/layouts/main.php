<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="bootstrap.css" rel="stylesheet">
    <?php $this->head() ?>
    <style> 
          .navbar
          {
            background-color: #EEE5DE;
           border-radius: 10px;
            border-top: 20px;
            border-bottom: 10px;
            

            
            }

            img{
                width: 200px;
                position:absolute;
                left:13%;
                top:15%;
                margin-left:-110px;
                margin-top:-45px;
            }
            
        </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('sos.png'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]); ?>

<?php  /*              $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];
            if (Yii::$app->user->isGuest) {

                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);*/
if(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Chefe de Segurança') {
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
    //        ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Iniciar', 'url' => ['/site/index1']],

//            ['label' => 'About', 'url' => ['/site/about']],
 //           ['label' => 'Contact', 'url' => ['/site/contact']],
            ['label' => 'Locais', 
            'items' => [
                    ['label' => 'Gerenciar Locais', 'url' => ['/local/index']],
                    ['label' => 'Gerenciar Sublocais', 'url' => ['/sublocal/index']]]],

            ['label' => 'Categorias', 'url' => ['/categoria/index']],
            ['label' => 'Natureza de Ocorrências', 'url' => ['/naturezaocorrencia/index']],
           ['label' => 'Denúncias', 'url' => ['/denuncia/index']],
            ['label' => 'Ocorrências',
            'items' => [
                 ['label' => 'Gerenciar Ocorrências', 'url' => ['/ocorrencia/index']],
                 ['label' => 'Ocorrências em Aberto', 'url' => ['/ocorrencia/emaberto']],
                 ['label' => 'Registrar Ocorrência', 'url' => ['/ocorrencia/create']],
                 ['label' => 'Gerar Relatórios', 'url' => ['/ocorrencia/relatorio']]]],
//                 ['label' => 'Fechar Ocorrências ', 'url' => ['/ocorrencia/index']]]],
             ['label' => 'Usuário',
 
            'items' => [
                 ['label' => 'Gerenciar Usuários', 'url' => ['/user/index']],
                 ['label' => 'Gerenciar Tipos de Usuário ', 'url' => ['/tipousuario/index']]]],
 
  
                Yii::$app->user->isGuest ?
                        ['label' => 'Entrar', 'url' => ['/site/login']] :
            //            ['label' => 'Logout (' . Yii::$app->user->identity->cpf . ')',
                            ['label' => 'Sair',
                        'items' => [
                            ['label' => 'Alterar senha ', 'url' => ['/user/alterarsenha']],        
                            ['label' => 'Logout', 'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']]]],
                ],
            ]);
            NavBar::end();
        } elseif (Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Segurança Terceirizada') {
    	 echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
           ['label' => 'Iniciar', 'url' => ['/site/index2']],

            ['label' => 'Ocorrências',
            'items' => [
  //               ['label' => 'Gerenciar Ocorrências', 'url' => ['/ocorrencia/index']],
//                 ['label' => 'Ocorrências em Aberto', 'url' => ['/ocorrencia/emaberto']],
                 ['label' => 'Registrar Ocorrência', 'url' => ['/ocorrencia/create']]]],
//                 ['label' => 'Fechar Ocorrências ', 'url' => ['/ocorrencia/index']]]], 
  
                     Yii::$app->user->isGuest ?
                        ['label' => 'Entrar', 'url' => ['/site/login']] :
            //            ['label' => 'Logout (' . Yii::$app->user->identity->cpf . ')',
                            ['label' => 'Sair',
                            'items' => [
                            ['label' => 'Alterar senha ', 'url' => ['/user/alterarsenha']],        
                            ['label' => 'Logout', 'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']]]],
                ],
            ]);
            NavBar::end();


        	# code...
        } else {
        	    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
                        ['label' => 'Entrar', 'url' => ['/site/login'],
                            'linkOptions' => ['data-method' => 'post']],
                ],
            ]);
            NavBar::end();
        }
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; SOS-UFAM <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
