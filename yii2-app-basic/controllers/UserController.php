<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\Tipousuario;
use app\models\TipousuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl; 
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [ 
        'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index', 'update', ''],
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'update'],
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
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())){
            if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->cpf]);
            }
            else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }
 /*   public function actionCreate()
    {
        $model = new Aluno();
        
          $arraycursos=ArrayHelper::map(CursoSearch::find()->all(),'id','nome');

        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id, 'arraycursos'=>$arraycursos]);
        } else {
            return $this->render('create', [
                'model' => $model, 
                'arraycursos'=>$arraycursos
            ]);
        }
    }*/



    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
     
        $arraytiposusuario=ArrayHelper::map(TipousuarioSearch::find()->all(),'idTipo','funcao');

        $model->idTipoUsuario = $model->idTipoUsuariobkp;

        if ($model->load(Yii::$app->request->post())) {
        
            if($model->save()) {
            return $this->redirect(['view', 'id' => $model->cpf, 'arraytiposusuario' => $arraytiposusuario]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'arraytiposusuario' => $arraytiposusuario
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionForgot()
    {

        if ( Yii::$app->request->post()) 
        {
            $cpf = new User();
            $cpf->load(Yii::$app->request->post());

           /// $usuario = User::find()->where(['email'=>$email])->one();
            //$usuario = User::findOne($cpf);
          //   echo($usuario->cpf);
            $usuario = new User();
            $usuario = User::findOne($cpf->cpf);
        //    print_r($usuario);
            if($usuario!=null) //se o usuario com email informado existe...
            {
                $domain = 'sandbox0d88942972964b89b6b8ef12520db517.mailgun.org';
                $key = 'key-4ff7c7a5e38505ed435d60be7006c3a2';

                $mailgun = new \MailgunApi( $domain, $key );

                $senha = $usuario->senhaAleatoria($usuario->cpf);

                $message = $mailgun->newMessage();
                $message->setFrom('admin@icomp.ufam.edu.br', 'SOS-UFAM');
                $message->addTo( $usuario->email); //destinatario...
                $message->setSubject('Nova Senha');
                $message->setText('Sua nova senha temporária é: ' . $senha);
                $message->send();

                return $this->render('enviada',[
                    'email' => $usuario->email
                ]);
            }
            else
            {
                return $this->render('naoachouemail', [
                                    'cpf' => $cpf
                ]);
            }
        }
        else
        {
            $model = new User();
            return $this->render('forgot', [
                'model' => $model
            ]);        
        }
    }
    public function actionAlterarsenha()
    {
        $model = $this->findModel(Yii::$app->user->identity->cpf);
        
        $cpf = $model->cpf;
        $funcao = $model->idTipoUsuario;
        $nome = $model->nome;
        $email = $model->email; 
        $model->senha = "";
        $model->confirmarSenha = "";
        
        if ($model->load(Yii::$app->request->post())) {
   /*         echo "senha".$model->senha;
            echo "cpf".$model->cpf;
            echo "idTipoUsuario".$model->idTipoUsuario;
            echo "email".$model->email;*/
            if (strcmp($model->senha, $model->confirmarSenha) == 0){
            $model->cpf = $cpf;
            $model->idTipoUsuario = $funcao;

               if (strcmp($model->idTipoUsuario, 'Chefe de Segurança') == 0)$model->idTipoUsuario = 1;
              elseif (strcmp($model->idTipoUsuario, 'Segurança Terceirizada') == 0)$model->idTipoUsuario = 2;
 
                $model->email = $email;
                $model->nome = $nome;

                $model->save();
              
                $arraytiposusuario=ArrayHelper::map(TipousuarioSearch::find()->all(),'idTipo','funcao');
                return $this->redirect(['view', 'id' => $model->cpf, 'arraytiposusuario' => $arraytiposusuario]);
            }else {
                    return $this->render('_formalterarsenha', [
                    'model' => $model,
                    
                ]);     
            }
         }
         else {
                return $this->render('_formalterarsenha', [
                    'model' => $model,
                    
                ]);
        }
    }

}
