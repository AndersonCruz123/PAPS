<?php

namespace app\controllers;

use Yii;
use app\models\Denuncia;
use app\models\DenunciaSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\Foto;
use app\controllers\FotoController;

/**
 * DenunciaController implements the CRUD actions for Denuncia model.
 */
class DenunciaController extends Controller
{
    public function behaviors()
    {
        if(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Chefe de Segurança') {
        return [ 
        'access' => [
                'class' => AccessControl::className(),
            //    'only' => ['index', 'update', 'delete', 'naoverificadas'],
                'rules' => [
                    [
                        'actions' => ['index','view', 'update','delete', 'naoverificadas'],
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
        else if(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Segurança Terceirizada') {
        return [ 
        'access' => [
                'class' => AccessControl::className(),
         //       'only' => ['index', 'update', 'delete', 'naoverificadas'],
                'rules' => [
                    [
                        'actions' => ['index','view', 'naoverificadas'],
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
        else if (Yii::$app->user->isGuest == true) {
        return [ 
        'access' => [
                'class' => AccessControl::className(),
                 'rules' => [
                    [
                        'actions' => ['create', 'sucesso'],
                        'allow' => true,
  //                     'roles' => ['@'],
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
     * Lists all Denuncia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DenunciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNaoverificadas()
    {
        $searchModel = new DenunciaSearch();
        $dataProvider = $searchModel->naoVerificadas(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Denuncia model.
     * @param integer $id
     * @ret4urn mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Denuncia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Denuncia();

        if ($model->load(Yii::$app->request->post())) {

         list ($dia, $mes, $ano) = split ('[/]', $model->data);
        $model->data = $ano.'-'.$mes.'-'.$dia;
        
           $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
           $path = Yii::$app->basePath.'/web/uploadFoto/';
           $model->status = 1;
            if($model->save()){
                foreach ($model->imageFiles as $file) {
                    $foto = new Foto();
                    $foto->idDenuncia = $model->idDenuncia;
                    $foto->endereco = $path . $file->baseName . '.' . $file->extension;
                    $foto->nome = $file->baseName . '.' . $file->extension;

                    $file->saveAs( $foto->endereco);
                    
                    $foto->save();

                    $foto = null;
                    }
                
                return $this->render('sucesso');
            } else {
            return $this->render('create', [
                'model' => $model,
            ]);
            }
      
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Denuncia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (strcmp($model->status, 'Não verificada') == 0)$model->status = 1;
        elseif (strcmp($model->status, 'Verdadeira') == 0)$model->status = 2;
        elseif (strcmp($model->status, 'Falsa') == 0)$model->status = 3;

        if ($model->load(Yii::$app->request->post())){

         list ($dia, $mes, $ano) = split ('[/]', $model->data);
        $model->data = $ano.'-'.$mes.'-'.$dia;
 
            if($model->save()) {
            return $this->redirect(['view', 'id' => $model->idDenuncia]);
            }
        else {
            return $this->render('update', [
                'model' => $model,
            ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Denuncia model.
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
     * Finds the Denuncia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Denuncia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Denuncia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
