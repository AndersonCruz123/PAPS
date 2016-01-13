<?php

namespace app\controllers;

use Yii;
use app\models\Sublocal;
use app\models\SublocalSearch;
use app\models\Local;
use app\models\LocalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * SublocalController implements the CRUD actions for Sublocal model.
 */
class SublocalController extends Controller
{
    public function behaviors()
    {
        if(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Chefe de Segurança') {
        return [ 
        'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'update', 'delete', 'view'],
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
    } else {
        return [ 
        'access' => [
                'class' => AccessControl::className(),
                //'only' => ['create', 'index', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['lists', 'sublocalselected'],
                        'allow' => true,
                   //     'roles' => ['@'],
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
    } 
    /**
     * Lists all Sublocal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SublocalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sublocal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionLists($id){
        $countSublocais = SubLocal::find()
            ->where(['idLocal' => $id])
            ->count();

        $subLocal = Sublocal::find()
            ->where(['idLocal' => $id])
            ->all();

        if($countSublocais > 0 ){
            foreach ($subLocal as $branch) {
                echo "<option value='".$branch->idSubLocal."'>".$branch->Nome."</option>";
            }

        }else{
            echo "<options>Não possui sublocal</options>";

        }
    }

    public function actionSublocalselected($idLocal, $idSublocal){
        $countSublocais = SubLocal::find()
            ->where(['idLocal' => $idLocal])
            ->count();

        $subLocal = Sublocal::find()
            ->where(['idLocal' => $idLocal])
            ->all();

        if($countSublocais > 0 ){
            foreach ($subLocal as $branch) {
                if ($branch->idSubLocal == $idSublocal){
                echo "<option value='".$branch->idSubLocal."' selected>".$branch->Nome."</option>";                    
                } else 
                echo "<option value='".$branch->idSubLocal."'>".$branch->Nome."</option>";
            }

        }else{
            echo "<options>Não possui sublocal</options>";

        }
    }



    /**
     * Creates a new Sublocal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sublocal();

       $arraylocal=ArrayHelper::map(LocalSearch::find()->all(),'idLocal','Nome');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->idSubLocal, 'arraylocal'=>$arraylocal]);
        } else {

            return $this->render('create', [
                'model' => $model,
             'arraylocal'=>$arraylocal
            ]);
        }
    }


    /**
     * Updates an existing Sublocal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

       $arraylocal=ArrayHelper::map(LocalSearch::find()->all(),'idLocal','Nome');
       $model->idLocal = $model->idLocalbkp;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->idSubLocal, 'arraylocal'=>$arraylocal]);
        } else {

            return $this->render('update', [
                'model' => $model,
             'arraylocal'=>$arraylocal
            ]);
        }
    }

    /**
     * Deletes an existing Sublocal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sublocal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sublocal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sublocal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
