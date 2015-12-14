<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'ocorrencia'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

     public function actionIndex1()
    {
        return $this->render('index1');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionTeste()
    {
        return $this->render('teste');
    }

    /*public function actionForgot()
    {

        if ( Yii::$app->request->post()) 
        {
            $email = Yii::$app->request->post('cpf');

            $usuario = User::find()->where(['email'=>$email])->one();
            //$usuario = User::findOne($cpf);
             print($usuario->cpf);
  
            if($usuario!=null) //se o usuario com email informado existe...
            {
                $domain = 'sandbox0d88942972964b89b6b8ef12520db517.mailgun.org';
                $key = 'key-4ff7c7a5e38505ed435d60be7006c3a2';

                $mailgun = new \MailgunApi( $domain, $key );

                $message = $mailgun->newMessage();
                $message->setFrom('admin@icomp.ufam.edu.br', 'SOS UFAM');
                $message->addTo( $usuario->email, $usuario->cpf); //destinatario...
                $message->setSubject('Nova Senha');
                $message->setText('Sua nova senha temporária é: ' . $usuario->senhaAleatoria($usuario));

                $message->send();
  print($usuario->cpf);
               // print_r($usuario);
                
                return $this->render('enviada');
            }
            else
            {
                return $this->render('emailnaoencontrado');
            }
        }
        else
        {
            return $this->render('forgot');
        }
    }*/

}
