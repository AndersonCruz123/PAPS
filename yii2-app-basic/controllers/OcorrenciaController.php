<?php

namespace app\controllers;

use Yii;
use app\models\Ocorrencia;
use app\models\OcorrenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\NaturezaocorrenciaSearch;
use app\models\LocalSearch;
use app\models\SublocalSearch;
use app\models\CategoriaSearch;
use app\models\Naturezaocorrencia;
use app\models\Local;
use app\models\Sublocal;
use app\models\Categoria;
use yii\web\UploadedFile;
use app\models\Foto;
/**
 * OcorrenciaController implements the CRUD actions for Ocorrencia model.
 */
class OcorrenciaController extends Controller
{
    public function behaviors()
    {
        return [ 
        'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index', 'update', 'emaberto'],
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'update', 'emaberto'],
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
     * Lists all Ocorrencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OcorrenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEmaberto()
    {
        $searchModel = new OcorrenciaSearch();
        $dataProvider = $searchModel->emAberto(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Ocorrencia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$model = $this->findModel($id);

/*    	if ($model->status == 1){
    		$model->status = 'Aberto';
    	} elseif ($model->status == 2) {
    		$model->status = 'Solucionado';
    	} elseif ($model->status == 3) {
    		$model->status = 'Não Solucionado';
    	}

       	if ($model->periodo == 1){
    		$model->periodo = 'Manhã';
    	} elseif ($model->periodo == 2) {
    		$model->periodo = 'Tarde';
    	} elseif ($model->periodo == 3) {
    		$model->periodo = 'Noite';
    	} elseif ($model->periodo == 4) {
    		$model->periodo = 'Madrugada';
    	}

    	$natureza = Naturezaocorrencia::findOne($model->idNatureza);
    	$sublocal = Sublocal::findOne($model->idSubLocal);
    	$categoria = Categoria::findOne($model->idCategoria);
    	$local = Local::findOne($sublocal->idLocal);

//    	echo 'idsublocal'.$sublocal->idLocal;
    	$model->idNatureza = $natureza->Nome;
    	$model->idSubLocal = $sublocal->Nome;
    	$model->idLocal = $sublocal->idLocal;
    	$model->idCategoria = $categoria->Nome;*/

    	$sublocal = Sublocal::findOne($model->idSubLocal);
       	$model->idSubLocal = $sublocal->Nome;
       	$model->idLocal = $sublocal->idLocal;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Ocorrencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ocorrencia();

        if ($model->load(Yii::$app->request->post())) {
           $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
           $path = Yii::$app->basePath.'/web/uploadFoto/';
		   $model->cpfUsuario = Yii::$app->user->identity->cpf;

            if($model->save()){
     //       	if (count ($model->imageFiles) >= 1) {
           	    foreach ($model->imageFiles as $file) {
           	    	$foto = new Foto();
           			$foto->idOcorrencia = $model->idOcorrencia;
           			$foto->comentario = $model->comentarioFoto;
           			$foto->endereco = $path . $file->baseName . '.' . $file->extension;

           			$file->saveAs( $foto->endereco);
                	
                	$foto->save();

                	$foto = null;
                	}
             //   }
                return $this->redirect(['view', 'id' => $model->idOcorrencia]);
            } else {
              //  echo "error da foto em".$image->error;
            }
      
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Ocorrencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (strcmp($model->status, 'Aberto') == 0)$model->status = 1;
        elseif (strcmp($model->status, 'Solucionado') == 0)$model->status = 2;
        elseif (strcmp($model->status, 'Não Solucionado') == 0)$model->status = 3;

        if (strcmp($model->periodo, 'Manhã') == 0)$model->periodo = 1;
        elseif (strcmp($model->periodo, 'Tarde') == 0)$model->periodo = 2;
        elseif (strcmp($model->periodo, 'Noite') == 0)$model->periodo = 3;
        elseif (strcmp($model->periodo, 'Madrugada') == 0)$model->periodo = 4;

        $model->idNatureza = $model->idNaturezabkp;
        $model->idSubLocal = $model->idSubLocalbkp;
        $model->idCategoria = $model->idCategoriabkp;

		$model->cpfUsuario = Yii::$app->user->identity->cpf;

    	$sublocal = Sublocal::findOne($model->idSubLocal);
       	$model->idLocal = $sublocal->idLocalbkp;

        if ($model->load(Yii::$app->request->post())) {
          $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
           $path = Yii::$app->basePath.'/web/uploadFoto/';
 
            if($model->save()){
       //     	if (count ($model->imageFiles) >= 1) {
           	    foreach ($model->imageFiles as $file) {
           	    	$foto = new Foto();
           			$foto->idOcorrencia = $model->idOcorrencia;
           			$foto->comentario = $model->comentarioFoto;
           			$foto->endereco = $path . $file->baseName . '.' . $file->extension;

           			$file->saveAs( $foto->endereco);
                	
                	$foto->save();

                	$foto = null;
                	}
   //             }
                return $this->redirect(['view', 'id' => $model->idOcorrencia]);
            } else {
              //  echo "error da foto em".$image->error;
            }
      
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ocorrencia model.
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
     * Finds the Ocorrencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ocorrencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ocorrencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
