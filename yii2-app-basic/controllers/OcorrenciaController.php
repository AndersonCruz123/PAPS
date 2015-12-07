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
use app\controllers\FotoController;
use mPDF;
/**
 * OcorrenciaController implements the CRUD actions for Ocorrencia model.
 */
class OcorrenciaController extends Controller
{
    public function behaviors()
    {

    	if(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Chefe de Segurança') {
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
    	elseif(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Segurança Terceirizada') {
        return [ 
        'access' => [
                'class' => AccessControl::className(),
 //               'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
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

    	$sublocal = Sublocal::findOne($model->idSubLocalbkp);
       	//$model->idSubLocal = $sublocal->Nome;
       	$model->idLocal = $sublocal->idLocal;

        $foto = FotoController::getFotoOcorrencia($model->idOcorrencia);
 		if ($foto != null) {
 		$model->comentarioFoto = $foto[0]->comentario;
        $model->fotos = $foto;
    	}
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
           			$foto->nome = $file->baseName . '.' . $file->extension;
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

    	$sublocal = Sublocal::findOne($model->idSubLocalbkp);
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

					$foto->nome = $file->baseName . '.' . $file->extension;
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

    public function actionPrintocorrencia($id)
    {
        $model = $this->findModel($id);
    	$sublocal = Sublocal::findOne($model->idSubLocal);
    	//$mode->idSubLocal = $sublocal->Nome;
         $mpdf = new mPDF('',    // mode - default ''
        '',    // format - A4, for example, default ''
        0,     // font size - default 0
        '',    // default font family
        15,    // margin_left
 15,    // margin right
 16,     // margin top
 16,    // margin bottom
 9,     // margin header
 9,     // margin footer

 'L');

		$stylesheet = file_get_contents("./../views/ocorrencia/relatorio/relatorio.css");

 		$mpdf->WriteHTML($stylesheet,1);
    
        $foto = FotoController::getFotoOcorrencia($model->idOcorrencia);
 		if ($foto != null) {
	 		$model->comentarioFoto = $foto[0]->comentario;
    	    $model->fotos = $foto;
    	}

    	if($model->procedimento == null) $model->procedimento = "Não informado";
    	if($model->dataConclusao == null) $model->dataConclusao = "Não informado";

		$tam = sizeof($model->fotos);
		$date = date("d/m/Y H:i:s ");

		if ($tam==0) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número da Ocorrencia: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
      	 	<h2> 7. Local:</h2> <p>".$model->idLocal. "</p>
      		<h2> 8. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
       		<h2> 9. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
        	<h2> 10. Descrição:</h2>  <p>".$model->descricao. "</p>
        	<h2> 11. Procedimento:</h2> <p>".$model->procedimento. "</p> 
        	<h2> 12. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
	        ";
	    }

		if ($tam==1) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número da Ocorrencia: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
      	 	<h2> 7. Local:</h2> <p>".$model->idLocal. "</p>
      		<h2> 8. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
       		<h2> 9. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
        	<h2> 10. Descrição:</h2>  <p>".$model->descricao. "</p>
        	<h2> 11. Procedimento:</h2> <p>".$model->procedimento. "</p> 
        	<h2> 12. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
        	<h2> 13. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
        	<h2> 14. Foto:</h2>
        	<img id='foto1' src='./../web/uploadFoto/".$model->fotos[0]->nome."' alt='".$model->fotos[0]->nome."'/>
        	"	        
	        ;
	    }

		else if ($tam==2) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número da Ocorrencia: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
      	 	<h2> 7. Local:</h2> <p>".$model->idLocal. "</p>
      		<h2> 8. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
       		<h2> 9. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
        	<h2> 10. Descrição:</h2>  <p>".$model->descricao. "</p>
        	<h2> 11. Procedimento:</h2> <p>".$model->procedimento. "</p> 
        	<h2> 12. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
        	<h2> 13. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
        	<h2> 14. Fotos:</h2>
        	<img id='foto1' src='./../web/uploadFoto/".$model->fotos[0]->nome."' alt='".$model->fotos[0]->nome."'/>
        	<img id='foto2' src='./../web/uploadFoto/".$model->fotos[1]->nome."' alt='".$model->fotos[1]->nome."'/>
        	"	        
	        ;
	    }

		else if ($tam==3) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número da Ocorrencia: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
      	 	<h2> 7. Local:</h2> <p>".$model->idLocal. "</p>
      		<h2> 8. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
       		<h2> 9. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
        	<h2> 10. Descrição:</h2>  <p>".$model->descricao. "</p>
        	<h2> 11. Procedimento:</h2> <p>".$model->procedimento. "</p> 
        	<h2> 12. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
        	<h2> 13. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
        	<h2> 14. Fotos:</h2>
        	<img id='foto1' src='./../web/uploadFoto/".$model->fotos[0]->nome."' alt='".$model->fotos[0]->nome."'/>
        	<img id='foto2' src='./../web/uploadFoto/".$model->fotos[1]->nome."' alt='".$model->fotos[1]->nome."'/>
        	<img id='foto3' src='./../web/uploadFoto/".$model->fotos[2]->nome."' alt='".$model->fotos[2]->nome."'/>
        	"	        
	        ;
	    }

		else if ($tam==4) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número da Ocorrencia: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
      	 	<h2> 7. Local:</h2> <p>".$model->idLocal. "</p>
      		<h2> 8. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
       		<h2> 9. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
        	<h2> 10. Descrição:</h2>  <p>".$model->descricao. "</p>
        	<h2> 11. Procedimento:</h2> <p>".$model->procedimento. "</p> 
        	<h2> 12. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
        	<h2> 13. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
        	<h2> 14. Fotos:</h2>
        	<img id='foto1' src='./../web/uploadFoto/".$model->fotos[0]->nome."' alt='".$model->fotos[0]->nome."'/>
        	<img id='foto2' src='./../web/uploadFoto/".$model->fotos[1]->nome."' alt='".$model->fotos[1]->nome."'/>
        	<img id='foto3' src='./../web/uploadFoto/".$model->fotos[2]->nome."' alt='".$model->fotos[2]->nome."'/>
        	<img id='foto4' src='./../web/uploadFoto/".$model->fotos[3]->nome."' alt='".$model->fotos[3]->nome."'/>
        	"	        
	        ;
	    }

		$mpdf->WriteHTML($html);
		$mpdf->Output();
        exit;
    }
}
