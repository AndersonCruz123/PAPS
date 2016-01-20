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
use app\models\Relatorio;
use app\models\Denuncia;
use yii\web\UploadedFile;
use app\models\Foto;
use app\controllers\FotoController;
use mPDF;
use yii\db\Query;
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
                'only' => ['create', 'createfromdenuncia' ,'index', 'indexrelatorio', 'update', 'delete', 'emaberto', 'relatorio', 'printocorrencia', 'printrelatorio'],
                'rules' => [
                    [
                        'actions' => ['create', 'createfromdenuncia', 'index', 'indexrelatorio','update','delete', 'emaberto', 'relatorio', 'printocorrencia', 'printrelatorio'],
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
                        'actions' => ['create', 'view'],
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
    public function actionCreatefromdenuncia($idDenuncia) {
        
        $model = new Ocorrencia();

        $denuncia = Denuncia::findOne($idDenuncia);
        
        $model->detalheLocal = $denuncia->detalheLocal;
        $model->data = $denuncia->data;
        $model->hora = $denuncia->hora;
        $model->descricao = $denuncia->descricao;

        $model->idLocal = $denuncia->idLocal;
        $model->idSubLocal = $denuncia->idSubLocalbkp;
        $sublocal = Sublocal::findOne($denuncia->idSubLocalbkp);
        $model->idLocal = $sublocal->idLocalbkp;
        $model->periodo = $denuncia->periodo;
        $model->comentarioFoto = $denuncia->comentarioFoto;

        if (strcmp($model->periodo, 'Manhã') == 0)$model->periodo = 1;
        elseif (strcmp($model->periodo, 'Tarde') == 0)$model->periodo = 2;
        elseif (strcmp($model->periodo, 'Noite') == 0)$model->periodo = 3;
        elseif (strcmp($model->periodo, 'Madrugada') == 0)$model->periodo = 4;

        if ($model->load(Yii::$app->request->post())) {

            list ($dia, $mes, $ano) = split ('[/]', $model->data);
            $model->data = $ano.'-'.$mes.'-'.$dia;

            if ($model->dataConclusao!=null){
              list ($dia, $mes, $ano) = split ('[/]', $model->dataConclusao);
              $model->dataConclusao = $ano.'-'.$mes.'-'.$dia;
            }

           $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
           $path = Yii::$app->basePath.'/web/uploadFoto/';
           $model->cpfUsuario = Yii::$app->user->identity->cpf;

            if($model->save()){
     //         if (count ($model->imageFiles) >= 1) {
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

              $foto = FotoController::getFotoDenuncia($denuncia->idDenuncia);
              foreach ($foto as $file) {
                $file->idOcorrencia = $model->idOcorrencia;
                $file->save();
               }

             //   }
                return $this->redirect(['view', 'id' => $model->idOcorrencia]);
            } else {
            return $this->render('createdenuncia', [
                'model' => $model,
            ]);
            }
      
        } else {
            return $this->render('createdenuncia', [
                'model' => $model,
            ]);
        }
    } 

    public function actionCreate()
    {
        $model = new Ocorrencia();
        $model->idLocal = 0;

        if ($model->load(Yii::$app->request->post())) {

            list ($dia, $mes, $ano) = split ('[/]', $model->data);
            $model->data = $ano.'-'.$mes.'-'.$dia;

            if ($model->dataConclusao!=null){
              list ($dia, $mes, $ano) = split ('[/]', $model->dataConclusao);
              $model->dataConclusao = $ano.'-'.$mes.'-'.$dia;
            }

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
                    list ($ano, $mes, $dia) = split ('[-]', $model->data);
                $model->data = $dia.'/'.$mes.'/'.$ano;
                
                if($model->dataConclusao!=null){
                    list ($ano, $mes, $dia) = split ('[-]', $model->dataConclusao);
                  $model->dataConclusao = $dia.'/'.$mes.'/'.$ano;  
                }          
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


        $model->idNatureza = $model->idNaturezabkp;
        $model->idSubLocal = $model->idSubLocalbkp;
        $model->idCategoria = $model->idCategoriabkp;

		$model->cpfUsuario = Yii::$app->user->identity->cpf;

    	$sublocal = Sublocal::findOne($model->idSubLocalbkp);
       	$model->idLocal = $sublocal->idLocalbkp;

        if ($model->load(Yii::$app->request->post())) {
          $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
           $path = Yii::$app->basePath.'/web/uploadFoto/';
 
            list ($dia, $mes, $ano) = split ('[/]', $model->data);
            $model->data = $ano.'-'.$mes.'-'.$dia;

            if ($model->dataConclusao!=null){
              list ($dia, $mes, $ano) = split ('[/]', $model->dataConclusao);
              $model->dataConclusao = $ano.'-'.$mes.'-'.$dia;
            }

            if($model->save()){
       //     	if (count ($model->imageFiles) >= 1) {

            if($model->comentarioFoto!=null){
              $foto = FotoController::getFotoOcorrencia($model->idOcorrencia);
              foreach ($foto as $file) {
                $file->comentario = $model->comentarioFoto;
                $file->save();
               }
            }

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
                list ($ano, $mes, $dia) = split ('[-]', $model->data);
                $model->data = $dia.'/'.$mes.'/'.$ano;
                
                if($model->dataConclusao!=null){
                    list ($ano, $mes, $dia) = split ('[-]', $model->dataConclusao);
                  $model->dataConclusao = $dia.'/'.$mes.'/'.$ano;  
                }
            return $this->render('update', [
                'model' => $model
            ]);
              //  echo "error da foto em".$image->error;
            }
      
        } else {

            return $this->render('update', [
                'model' => $model
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

    protected function findMonths($mes) {
    	if ($mes == 1) return "Janeiro";
    	else if ($mes == 2) return "Fevereiro";
    	else if ($mes == 3) return "Março";
    	else if ($mes == 4) return "Abril";
    	else if ($mes == 5) return "Maio";
    	else if ($mes == 6) return "Junho";    	    	    	    	
    	else if ($mes == 7) return "Julho";
    	else if ($mes == 8) return "Agosto";
    	else if ($mes == 9) return "Setembro";
    	else if ($mes == 10) return "Outubro";
    	else if ($mes == 11) return "Novembro";    	    	    	
    	else if ($mes == 12) return "Dezembro";
    }


    protected function findMonthsNumber($mes) {
        if (strcmp ($mes, 'Jan') == 0) return 1;
        else if (strcmp ($mes, 'Fev') == 0) return 2;
        else if (strcmp ($mes, 'Mar') == 0) return 3;
        else if (strcmp ($mes, 'Abr') == 0) return 4;
        else if (strcmp ($mes, 'Mai') == 0) return 5;
        else if (strcmp ($mes, 'Jun') == 0) return 6;                             
        else if (strcmp ($mes, 'Jul') == 0) return 7;
        else if (strcmp ($mes, 'Ago') == 0) return 8;
        else if (strcmp ($mes, 'Set') == 0) return 9;
        else if (strcmp ($mes, 'Out') == 0)return 10;
        else if (strcmp ($mes, 'Nov') == 0) return 11;                      
        else if (strcmp ($mes, 'Dez') == 0) return 12;
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
  //  	if($model->dataConclusao == null) $model->dataConclusao = "Não informado";
    //  else {
      //  list ($ano, $mes, $dia) = split ('[-]', $model->dataConclusao);
        //$model->dataConclusao = $dia.'/'.$mes.'/'.$ano;        
      //}       
      
   //   list ($ano, $mes, $dia) = split ('[-]', $model->data);
     // $model->data = $dia.'/'.$mes.'/'.$ano;

		$tam = sizeof($model->fotos);
		$date = date("d/m/Y H:i:s ");

		if ($tam==0) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número de Registro da Ocorrência: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
          <h2> 7. Período do acontecimento da ocorrência:</h2> <p>".$model->periodo. "</p>
      	 	<h2> 8. Local:</h2> <p>".$model->idLocal. "</p>
      		<h2> 9. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
       		<h2> 10. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 11. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 12. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
        	<h2> 13. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
	        ";
	    }

		if ($tam==1) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número de Registro da Ocorrência: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
          <h2> 7. Período do acontecimento da ocorrência:</h2> <p>".$model->periodo. "</p>
          <h2> 8. Local:</h2> <p>".$model->idLocal. "</p>
          <h2> 9. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
          <h2> 10. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 11. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 12. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
          <h2> 13. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
        	<h2> 14. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
        	<h2> 15. Foto:</h2>
        	<img id='foto1' src='./../web/uploadFoto/".$model->fotos[0]->nome."' alt='".$model->fotos[0]->nome."'/>
        	"	        
	        ;
	    }

		else if ($tam==2) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número de Registro da Ocorrência: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
          <h2> 7. Período do acontecimento da ocorrência:</h2> <p>".$model->periodo. "</p>
          <h2> 8. Local:</h2> <p>".$model->idLocal. "</p>
          <h2> 9. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
          <h2> 10. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 11. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 12. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
          <h2> 13. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
          <h2> 14. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
          <h2> 15. Foto:</h2>
          <img id='foto1' src='./../web/uploadFoto/".$model->fotos[0]->nome."' alt='".$model->fotos[0]->nome."'/>
        	<img id='foto2' src='./../web/uploadFoto/".$model->fotos[1]->nome."' alt='".$model->fotos[1]->nome."'/>
        	"	        
	        ;
	    }

		else if ($tam==3) {
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span> 
        	<h2> 1. Número de Registro da Ocorrência: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
          <h2> 7. Período do acontecimento da ocorrência:</h2> <p>".$model->periodo. "</p>
          <h2> 8. Local:</h2> <p>".$model->idLocal. "</p>
          <h2> 9. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
          <h2> 10. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 11. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 12. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
          <h2> 13. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
          <h2> 14. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
          <h2> 15. Foto:</h2>
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
        	<h2> 1. Número de Registro da Ocorrência: ".$model->idOcorrencia. "</h2>
        	<h2> 2. Status:</h2> <p>".$model->status. "</p>
        	<h2> 3. Categoria da Ocorrência:</h2> <p>".$model->idCategoria. "</p>
        	<h2> 4. Natureza da Ocorrência:</h2> <p>".$model->idNatureza. "</p>
        	<h2> 5. Data do acontecimento da ocorrência:</h2> <p>".$model->data. "</p>
        	<h2> 6. Hora do acontecimento da ocorrência:</h2> <p>".$model->hora. "</p>
          <h2> 7. Período do acontecimento da ocorrência:</h2> <p>".$model->periodo. "</p>
          <h2> 8. Local:</h2> <p>".$model->idLocal. "</p>
          <h2> 9. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
          <h2> 10. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 11. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 12. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
          <h2> 13. Data conclusão:</h2> <p>".$model->dataConclusao. "</p>
          <h2> 14. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
          <h2> 15. Foto:</h2>
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

protected function generateTablePeriodo($option, $dataIni, $dataFim, $dataInicial, $dataFinal, $status, $periodo, $idLocal, $idNatureza, $idCategoria) {

		$tabelaPeriodo = "";
		if ($option == 1) {

          list ($ano, $mes, $dia) = split ('[-]', $dataFim);
      	
   		//TABELA PERIODO
       		$tabelaPeriodo .= "<table border='1' width='1000' align='center' id='tabelaperiodo'>
      	  <caption>Quantidade de ocorrências por período do mês de ". $this->findMonths($mes)." do ano de ".$ano."</caption>
           <thead>
           <tr class='header'>
             <td>Período</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
       } else {
       		$tabelaPeriodo .= "<table border='1' width='1000' align='center' id='tabelaperiodo'>
      	  <caption>Quantidade de ocorrências por período do mês de ". $dataInicial." a ".$dataFinal."</caption>
           <thead>
           <tr class='header'>
             <td>Período</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
       }

	  	$connection = \Yii::$app->db;
	  
	  if ($periodo == 0) {
        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 1'; 
		 if ($status!=0) $stringsql .= ' AND status = '.$status;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlManha = $connection->createCommand($stringsql);
        $manha = $sqlManha->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 2';
		 if ($status!=0) $stringsql .= ' AND status = '.$status;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlTarde = $connection->createCommand($stringsql);
        $tarde = $sqlTarde->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 3';
		 if ($status!=0) $stringsql .= ' AND status = '.$status;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlNoite = $connection->createCommand($stringsql);
        $noite = $sqlNoite->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 4';
		 if ($status!=0) $stringsql .= ' AND status = '.$status;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';        
        $sqlMadrugada = $connection->createCommand($stringsql);
        $madrugada = $sqlMadrugada->queryScalar();        

		 		$tabelaPeriodo .= "<tr>";
		 		$tabelaPeriodo .= "<td>Manhã</td>";
            	$tabelaPeriodo .= "<td>".$manha."</td>";
            	$tabelaPeriodo .= "</tr>";

		 		$tabelaPeriodo .= "<tr>";
		 		$tabelaPeriodo .= "<td>Tarde</td>";
            	$tabelaPeriodo .= "<td>".$tarde."</td>";
            	$tabelaPeriodo .= "</tr>";

		 		$tabelaPeriodo .= "<tr>";
		 		$tabelaPeriodo .= "<td>Noite</td>";
            	$tabelaPeriodo .= "<td>".$noite."</td>";
            	$tabelaPeriodo .= "</tr>";

		 		$tabelaPeriodo .= "<tr>";
		 		$tabelaPeriodo .= "<td>Madrugada</td>";
            	$tabelaPeriodo .= "<td>".$madrugada."</td>";
            	$tabelaPeriodo .= "</tr>";
     //       $color = !$color;
       	} else if ($periodo == 1) {

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 1'; 
		 if ($status!=0) $stringsql .= ' AND status = '.$status;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlManha = $connection->createCommand($stringsql);
        $manha = $sqlManha->queryScalar();

		 		$tabelaPeriodo .= "<tr>";
		 		$tabelaPeriodo .= "<td>Manhã</td>";
            	$tabelaPeriodo .= "<td>".$manha."</td>";
            	$tabelaPeriodo .= "</tr>";

       	} else if ($periodo == 2) {

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 2';
		 if ($status!=0) $stringsql .= ' AND status = '.$status;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlTarde = $connection->createCommand($stringsql);
        $tarde = $sqlTarde->queryScalar();

		 		$tabelaPeriodo .= "<tr>";
		 		$tabelaPeriodo .= "<td>Tarde</td>";
            	$tabelaPeriodo .= "<td>".$tarde."</td>";
            	$tabelaPeriodo .= "</tr>";
       	} else if ($periodo == 3) {

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 3';
		 if ($status!=0) $stringsql .= ' AND status = '.$status;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlNoite = $connection->createCommand($stringsql);
        $noite = $sqlNoite->queryScalar();

		 		$tabelaPeriodo .= "<tr>";
		 		$tabelaPeriodo .= "<td>Noite</td>";
            	$tabelaPeriodo .= "<td>".$noite."</td>";
            	$tabelaPeriodo .= "</tr>";

       	} else if ($periodo == 4) {

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 4';
		 if ($status!=0) $stringsql .= ' AND status = '.$status;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';        
        $sqlMadrugada = $connection->createCommand($stringsql);
        $madrugada = $sqlMadrugada->queryScalar();        

		 		$tabelaPeriodo .= "<tr>";
		 		$tabelaPeriodo .= "<td>Madrugada</td>";
            	$tabelaPeriodo .= "<td>".$madrugada."</td>";
            	$tabelaPeriodo .= "</tr>";
    	}

       	$tabelaPeriodo .= "</table>";
        return $tabelaPeriodo;
}

protected function generateTableStatus($option, $dataIni, $dataFim, $dataInicial, $dataFinal, $status, $periodo, $idLocal, $idNatureza, $idCategoria) {

		$tabelaStatus = "";
		if ($option == 1) {

          list ($ano, $mes, $dia) = split ('[-]', $dataFim);
		
       		//TABELA STATUS
       		$tabelaStatus .= "<table border='1' width='1000' align='center' id='tabelastatus'>
      	  <caption>Quantidade de ocorrências por status do mês de ". $this->findMonths($mes)." do ano de ".$ano."</caption>
           <thead>
           <tr class='header'>
             <td>Status</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
		} else {
       		$tabelaStatus .= "<table border='1' width='1000' align='center' id='tabelastatus'>
      	  <caption>Quantidade de ocorrências por status do mês de ". $dataInicial." a ".$dataFinal."</caption>
           <thead>
           <tr class='header'>
             <td>Status</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
		}

	  	$connection = \Yii::$app->db;

	  	if ($status == 0){
	
	   	$stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 1';
		 if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';

		$sqlAberto = $connection->createCommand($stringsql);
        $aberto = $sqlAberto->queryScalar();
 
 		$stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 2';
		 if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlSolucionado = $connection->createCommand($stringsql);
        $solucionado = $sqlSolucionado->queryScalar();


		$stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 3';
		 if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';		       
        $sqlNotSolucionado = $connection->createCommand($stringsql);
        $notsolucionado = $sqlNotSolucionado->queryScalar();

		 		$tabelaStatus .= "<tr>";
            	$tabelaStatus .= "<td>Aberta</td>";
            	$tabelaStatus .= "<td>".$aberto."</td>";
            	$tabelaStatus .= "</tr>";

		 		$tabelaStatus .= "<tr>";
            	$tabelaStatus .= "<td>Solucionada</td>";		 		
            	$tabelaStatus .= "<td>".$solucionado."</td>";
            	$tabelaStatus .= "</tr>";

		 		$tabelaStatus .= "<tr>";
            	$tabelaStatus .= "<td>Não solucionada</td>";		 		
            	$tabelaStatus .= "<td>".$notsolucionado."</td>";
            	$tabelaStatus .= "</tr>";
     //       $color = !$color;
       	} else if ($status == 1) {

	   	$stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 1';
		 if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';

		$sqlAberto = $connection->createCommand($stringsql);
        $aberto = $sqlAberto->queryScalar();

		 		$tabelaStatus .= "<tr>";
            	$tabelaStatus .= "<td>Aberta</td>";
            	$tabelaStatus .= "<td>".$aberto."</td>";
            	$tabelaStatus .= "</tr>";

       	} else if ($status == 2) {

 		$stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 2';
		 if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlSolucionado = $connection->createCommand($stringsql);
        $solucionado = $sqlSolucionado->queryScalar();

        		$tabelaStatus .= "<tr>";
            	$tabelaStatus .= "<td>Solucionada</td>";		 		
            	$tabelaStatus .= "<td>".$solucionado."</td>";
            	$tabelaStatus .= "</tr>";
        } else if ($status == 3) {

		$stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 3';
		 if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
		 if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';		       
        $sqlNotSolucionado = $connection->createCommand($stringsql);
        $notsolucionado = $sqlNotSolucionado->queryScalar();

		 		$tabelaStatus .= "<tr>";
            	$tabelaStatus .= "<td>Não solucionada</td>";		 		
            	$tabelaStatus .= "<td>".$notsolucionado."</td>";
            	$tabelaStatus .= "</tr>";
        }

       	$tabelaStatus .= "</table>";
       	return $tabelaStatus;
}

protected function generateTableNatureza($option, $dataIni, $dataFim, $dataInicial, $dataFinal, $status, $periodo, $idLocal, $idNatureza, $idCategoria) {

	  	$connection = \Yii::$app->db;
		$tabelaNatureza = "";
		if ($option == 1) {

          list ($ano, $mes, $dia) = split ('[-]', $dataFim);

       		//TABELA NATUREZA
       		$tabelaNatureza .= "<table border='1' width='1000' align='center' id='tabelanatureza'>
      	  <caption>Quantidade de ocorrências por natureza do mês de ". $this->findMonths($mes)." do ano de ".$ano."</caption>
           <thead>
           <tr class='header'>
             <td>Natureza</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
       } else {
    		$tabelaNatureza .= "<table border='1' width='1000' align='center' id='tabelanatureza'>
      	  <caption>Quantidade de ocorrências por natureza do mês de ". $dataInicial." a ".$dataFinal."</caption>
           <thead>
           <tr class='header'>
             <td>Natureza</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
        }
        
        $stringsql = "SELECT naturezaocorrencia.Nome as natureza, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN naturezaocorrencia ON naturezaocorrencia.idNatureza = ocorrencia.idNatureza
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
		 if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
		 if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;		 
		 if ($idLocal!=0) $stringsql .= ' AND ocorrencia.idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';			    
			    $stringsql .= " GROUP BY ocorrencia.idNatureza
				ORDER BY quantidade  DESC";

        $sqlNatureza = $connection->createCommand($stringsql);
        $rstnatureza = $sqlNatureza->queryAll();

        foreach ($rstnatureza as $reg):
		 	$tabelaNatureza .= "<tr>";
            $tabelaNatureza .= "<td>{$reg['natureza']}</td>";
            $tabelaNatureza .= "<td>{$reg['quantidade']}</td>";
            $tabelaNatureza .= "</tr>";
        endforeach;

       		$tabelaNatureza .= "</table>";
       		return $tabelaNatureza;
}

protected function generateTableCategoria($option, $dataIni, $dataFim, $dataInicial, $dataFinal, $status, $periodo, $idLocal, $idNatureza, $idCategoria) {

	  	$connection = \Yii::$app->db;
		$tabelaCategoria = "";
		if ($option == 1) {

          list ($ano, $mes, $dia) = split ('[-]', $dataFim);

       		$tabelaCategoria .= "<table border='1' width='1000' align='center' id='tabelacategoria'>
      	  <caption>Quantidade de ocorrências por categoria do mês de ". $this->findMonths($mes)." do ano de ".$ano."</caption>
           <thead>
           <tr class='header'>
             <td>Categoria</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
       } else {
       		$tabelaCategoria .= "<table border='1' width='1000' align='center' id='tabelacategoria'>
      	  <caption>Quantidade de ocorrências por categoria do mês de ". $dataInicial." a ".$dataFinal."</caption>
           <thead>
           <tr class='header'>
             <td>Categoria</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
        }

       $stringsql = "SELECT categoria.Nome as categoria, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN categoria ON categoria.idCategoria = 	ocorrencia.idCategoria
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
		 if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
		 if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;		 
		 if ($idLocal!=0) $stringsql .= ' AND ocorrencia.idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';			    
		$stringsql .= " GROUP BY ocorrencia.idCategoria ORDER BY quantidade  DESC";


        $sqlCategoria = $connection->createCommand($stringsql);
        $rstcategoria = $sqlCategoria->queryAll();

        foreach ($rstcategoria as $reg):
		 	$tabelaCategoria .= "<tr>";
            $tabelaCategoria .= "<td>{$reg['categoria']}</td>";
            $tabelaCategoria .= "<td>{$reg['quantidade']}</td>";
            $tabelaCategoria .= "</tr>";
        endforeach;

       		$tabelaCategoria .= "</table>";        
       		return $tabelaCategoria;
}

protected function generateTableLocal($option, $dataIni, $dataFim, $dataInicial, $dataFinal, $status, $periodo, $idLocal, $idNatureza, $idCategoria) {

	  	$connection = \Yii::$app->db;
		$tabelaLocal = "";
		if ($option == 1) {
          list ($ano, $mes, $dia) = split ('[-]', $dataFim);
           $tabelaLocal .= "<table border='1' width='1000' align='center' id='tabelalocal'>
      	  <caption>Quantidade de ocorrências por local do mês de ". $this->findMonths($mes)." do ano de ".$ano."</caption>
           <thead>
           <tr class='header'>
             <td>Local</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";

      } else {
       		$tabelaLocal .= "<table border='1' width='1000' align='center' id='tabelalocal'>
      	  <caption>Quantidade de ocorrências por local do mês de ". $dataInicial." a ".$dataFinal."</caption>
           <thead>
           <tr class='header'>
             <td>Local</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";
      }
        $stringsql = "SELECT local.Nome as nomelocal, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN sublocal ON sublocal.idSubLocal = 	ocorrencia.idSubLocal
				JOIN local ON sublocal.idLocal= local.idLocal
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
		 if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
		 if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;		 
		 if ($idLocal!=0) $stringsql .= ' AND local.idLocal = '.$idLocal;			    
		$stringsql .= " GROUP BY local.idLocal ORDER BY quantidade  DESC";

        
        $sqlLocal = $connection->createCommand($stringsql);      
        $rstlocal = $sqlLocal->queryAll();

        foreach ($rstlocal as $reg):
		 	$tabelaLocal .= "<tr>";
            $tabelaLocal .= "<td>{$reg['nomelocal']}</td>";
            $tabelaLocal .= "<td>{$reg['quantidade']}</td>";
            $tabelaLocal .= "</tr>";
        endforeach;

       		$tabelaLocal .= "</table>";
       		return $tabelaLocal;

}

protected function generateTableOcorrencia($option, $dataIni, $dataFim, $dataInicial, $dataFinal, $status, $periodo, $idLocal, $idNatureza, $idCategoria) {

	  	$connection = \Yii::$app->db;
		$tabelaOco = "";
		if ($option == 1) {
          list ($ano, $mes, $dia) = split ('[-]', $dataFim);			
      	  $tabelaOco .= "<table border='1' width='1000' align='center' id='tabelaocorrencia'>
      	  <caption>Quadro de ocorrências do mês de ". $this->findMonths($mes)." do ano de ".$ano."</caption>
           <thead>
           <tr class='header'>
             <td>N Registro</td>
             <td>Categoria</td>
             <td>Natureza</td>
             <td>Local</td>
             <td>Sublocal</td>
             <td>Status</td>
             <td>Período</td>
             <td>Data</td>
           </tr>
           </thead>
           ";		
		} else {
      	  
      	  $tabelaOco .= "<table border='1' width='1000' align='center' id='tabelaocorrencia'>
      	  <caption>Quadro de ocorrências do período de ". $dataInicial." a ".$dataFinal."</caption>
           <thead>
           <tr class='header'>
             <td>N Registro</td>
             <td>Categoria</td>
             <td>Natureza</td>
             <td>Local</td>
             <td>Sublocal</td>
             <td>Status</td>
             <td>Período</td>
             <td>Data</td>
           </tr>
           </thead>
           ";
		}

        $stringsql = "SELECT ocorrencia.idOcorrencia as idOcorrencia, categoria.Nome as categoriaNome, 
        naturezaocorrencia.Nome as naturezaNome, local.Nome as localNome, sublocal.Nome as sublocalNome, 
        ocorrencia.status as status, ocorrencia.periodo as periodo, ocorrencia.data as data
				FROM ocorrencia
				JOIN sublocal ON sublocal.idSubLocal = 	ocorrencia.idSubLocal
				JOIN categoria ON categoria.idCategoria = ocorrencia.idCategoria
				JOIN naturezaocorrencia ON naturezaocorrencia.idNatureza = ocorrencia.idNatureza				
				JOIN local ON sublocal.idLocal= local.idLocal
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
		 if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
		 if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
	 	 if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;		 
	 	 if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;		 
		 if ($idLocal!=0) $stringsql .= ' AND local.idLocal = '.$idLocal;
		$stringsql .= " ORDER BY ocorrencia.idOcorrencia ASC";

        
        $sqlOcorrencia = $connection->createCommand($stringsql);      
        $rstocorencia = $sqlOcorrencia->queryAll();
		 	
		 	foreach ($rstocorencia as $reg):
  //          $tabela .= ($color) ? "<tr>" : "<tr class=\'zebra\''>";
		 		//{$reg['nomelocal']}
		 		if ($reg['status'] == 1) $status = "Aberta";
		 		else if ($reg['status'] == 2) $status = "Solucionada";
		 		else if ($reg['status'] == 3) $status = "Não solucionada";

		 		if ($reg['periodo'] == 1) $periodo = "Manhã";
		 		else if ($reg['periodo'] == 2) $periodo = "Tarde";
		 		else if ($reg['periodo'] == 3) $periodo = "Noite";
		 		else if ($reg['periodo'] == 4) $periodo = "Madrugada";

        list ($ano, $mes, $dia) = split ('[-]', $reg['data']);
        $data = $dia.'/'.$mes.'/'.$ano;

		 		$tabelaOco .= "<tr>";
            	$tabelaOco .= "<td>{$reg['idOcorrencia']}</td>";
            	$tabelaOco .= "<td>{$reg['categoriaNome']}</td>";
            	$tabelaOco .= "<td>{$reg['naturezaNome']}</td>";
            	$tabelaOco .= "<td>{$reg['localNome']}</td>";
            	$tabelaOco .= "<td>{$reg['sublocalNome']}</td>";
            	$tabelaOco .= "<td>".$status."</td>";
           		$tabelaOco .= "<td>".$periodo."</td>";
            	$tabelaOco .= "<td>".$data."</td>";
            	$tabelaOco .= "</tr>";
     //       $color = !$color;
        	endforeach;

       		$tabelaOco .= "</table>";
       		return $tabelaOco;
}
 
  public function actionPrintrelatorio($periodo, $idCategoria, $status, $idNatureza, $idLocal, $dataInicial, $dataFinal, $radiobutton) {

        $model = new Relatorio();

        $model->periodo = $periodo;
        $model->idCategoria = $idCategoria;
        $model->status = $status;
        $model->idNatureza = $idNatureza;
        $model->idLocal = $idLocal;
        $model->dataInicial = $dataInicial;
        $model->dataFinal = $dataFinal;
        $model->radiobutton = $radiobutton;

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

    $date = date("d/m/Y H:i:s ");
        $tabelaOco = "";
        $tabelaPeriodo = "";
        $tabelaStatus = "";
        $tabelaNatureza = "";
        $tabelaLocal = "";
        $tabelaCategoria = "";


      $connection = \Yii::$app->db;

        list ($dia, $mes, $ano) = split ('[/]', $model->dataInicial);
        $dataIni = $ano.'-'.$mes.'-'.$dia;

        list ($dia, $mes, $ano) = split ('[/]', $model->dataFinal);
        $dataFim = $ano.'-'.$mes.'-'.$dia;
  

      $stringsql = "SELECT COUNT(idOcorrencia) as cont FROM ocorrencia
        JOIN sublocal ON sublocal.idSubLocal =  ocorrencia.idSubLocal
        JOIN categoria ON categoria.idCategoria = ocorrencia.idCategoria
        JOIN naturezaocorrencia ON naturezaocorrencia.idNatureza = ocorrencia.idNatureza        
        JOIN local ON sublocal.idLocal= local.idLocal
        WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
     if ($model->status!=0) $stringsql .= ' AND ocorrencia.status = '.$model->status;
     if ($model->periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$model->periodo;
     if ($model->idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$model->idCategoria;     
     if ($model->idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$model->idNatureza;    
     if ($model->idLocal!=0) $stringsql .= ' AND local.idLocal = '.$model->idLocal;
        $sqlSolucionado = $connection->createCommand($stringsql);
        $total = $sqlSolucionado->queryScalar();
      
          $html = "
          <img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
        <span id='data'><b>Gerado em: ".$date."</b></span>
        <h3>Total de ocorrências no período selecionado: ".$total."</h3>        
          ".$this->generateTableOcorrencia($model->radiobutton, $dataIni, $dataFim, $model->dataInicial, $model->dataFinal, $model->status, $model->periodo, 
            $model->idLocal, $model->idNatureza, $model->idCategoria)."
          ".$this->generateTablePeriodo($model->radiobutton, $dataIni, $dataFim, $model->dataInicial, $model->dataFinal, $model->status, $model->periodo, 
            $model->idLocal, $model->idNatureza, $model->idCategoria)."
          ".$this->generateTableStatus($model->radiobutton, $dataIni, $dataFim, $model->dataInicial, $model->dataFinal, $model->status, $model->periodo, 
            $model->idLocal, $model->idNatureza, $model->idCategoria)."
          ".$this->generateTableNatureza($model->radiobutton, $dataIni, $dataFim, $model->dataInicial, $model->dataFinal, $model->status, $model->periodo, 
            $model->idLocal, $model->idNatureza, $model->idCategoria)."         
          ".$this->generateTableCategoria($model->radiobutton, $dataIni, $dataFim, $model->dataInicial, $model->dataFinal, $model->status, $model->periodo, 
            $model->idLocal, $model->idNatureza, $model->idCategoria)."
          ".$this->generateTableLocal($model->radiobutton, $dataIni, $dataFim, $model->dataInicial, $model->dataFinal, $model->status, $model->periodo, 
            $model->idLocal, $model->idNatureza, $model->idCategoria)."
          ";

    $mpdf->WriteHTML($html);
    $mpdf->Output();
        exit;
         
        } /*else {
            return $this->render('relatorio', [
                'model' => $model,
            ]);
        }*/ 
  public function actionPrintgraficos($periodo, $idCategoria, $status, $idNatureza, $idLocal, $dataInicial, $dataFinal, $radiobutton) {

        $model = new Relatorio();

        $model->periodo = $periodo;
        $model->idCategoria = $idCategoria;
        $model->status = $status;
        $model->idNatureza = $idNatureza;
        $model->idLocal = $idLocal;
        $model->dataInicial = $dataInicial;
        $model->dataFinal = $dataFinal;
        $model->radiobutton = $radiobutton;

        list ($dia, $mes, $ano) = split ('[/]', $model->dataInicial);
        $dataIni = $ano.'-'.$mes.'-'.$dia;

        list ($dia, $mes, $ano) = split ('[/]', $model->dataFinal);
        $dataFim = $ano.'-'.$mes.'-'.$dia;


      $connection = \Yii::$app->db;

      $arrayStatus = array(0 => -1);
      $arrayPeriodo = array(0 => -1);
    
    if ($periodo == 0) {
        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 1'; 
     if ($status!=0) $stringsql .= ' AND status = '.$status;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlManha = $connection->createCommand($stringsql);
        $manha = $sqlManha->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 2';
     if ($status!=0) $stringsql .= ' AND status = '.$status;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlTarde = $connection->createCommand($stringsql);
        $tarde = $sqlTarde->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 3';
     if ($status!=0) $stringsql .= ' AND status = '.$status;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlNoite = $connection->createCommand($stringsql);
        $noite = $sqlNoite->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 4';
     if ($status!=0) $stringsql .= ' AND status = '.$status;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';        
        $sqlMadrugada = $connection->createCommand($stringsql);
        $madrugada = $sqlMadrugada->queryScalar();        
      
        $arrayPeriodo = array(0 => $manha, 1 => $tarde, 2 => $noite, 3 => $madrugada );
      }

      if ($status == 0){
  
      $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 1';
     if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';

    $sqlAberto = $connection->createCommand($stringsql);
        $aberto = $sqlAberto->queryScalar();
 
    $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 2';
     if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlSolucionado = $connection->createCommand($stringsql);
        $solucionado = $sqlSolucionado->queryScalar();


    $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 3';
     if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';           
        $sqlNotSolucionado = $connection->createCommand($stringsql);
        $notsolucionado = $sqlNotSolucionado->queryScalar();
        
        $arrayStatus = array(0 => $aberto, 1=>$solucionado, 2=>$notsolucionado);
        }

        $arrayNatureza['quantidade'][1] = -1;
        if($idNatureza==0){
        $stringsql = "SELECT naturezaocorrencia.Nome as natureza, COUNT(ocorrencia.idOcorrencia) as quantidade
        FROM ocorrencia
        JOIN naturezaocorrencia ON naturezaocorrencia.idNatureza = ocorrencia.idNatureza
        WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
     if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
     if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;     
     if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;    
     if ($idLocal!=0) $stringsql .= ' AND ocorrencia.idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';         
          $stringsql .= " GROUP BY ocorrencia.idNatureza
        ORDER BY quantidade  DESC";

        $sqlNatureza = $connection->createCommand($stringsql);
        $rstnatureza = $sqlNatureza->queryAll();
        
        $i=0;
        
        $arrayNatureza = array('nome' => array(), 'quantidade' => array());
        foreach ($rstnatureza as $reg):
        $arrayNatureza['nome'][$i] = $reg['natureza'];
        $arrayNatureza['quantidade'][$i] = $reg['quantidade'];

        $i=$i+1;
        
        endforeach;
      }

        $arrayCategoria['quantidade'][1] = -1;
      if($idCategoria==0){
       $stringsql = "SELECT categoria.Nome as categoria, COUNT(ocorrencia.idOcorrencia) as quantidade
        FROM ocorrencia
        JOIN categoria ON categoria.idCategoria =   ocorrencia.idCategoria
        WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
     if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
     if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;     
     if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;    
     if ($idLocal!=0) $stringsql .= ' AND ocorrencia.idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';         
    $stringsql .= " GROUP BY ocorrencia.idCategoria ORDER BY quantidade  DESC";


        $sqlCategoria = $connection->createCommand($stringsql);
        $rstcategoria = $sqlCategoria->queryAll();

         $i=0;
        
        $arrayCategoria = array('nome' => array(), 'quantidade' => array());
        
        foreach ($rstcategoria as $reg):
        $arrayCategoria['nome'][$i] = $reg['categoria'];
        $arrayCategoria['quantidade'][$i] = $reg['quantidade'];
        $i=$i+1;
        endforeach;      
      }

        $arrayLocal = array('nome' => array(), 'quantidade' => array());
        $arrayLocal['quantidade'][1] = -1;

      if($idLocal==0){
        $stringsql = "SELECT local.Nome as nomelocal, COUNT(ocorrencia.idOcorrencia) as quantidade
        FROM ocorrencia
        JOIN sublocal ON sublocal.idSubLocal =  ocorrencia.idSubLocal
        JOIN local ON sublocal.idLocal= local.idLocal
        WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
     if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
     if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;     
     if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;    
     if ($idLocal!=0) $stringsql .= ' AND local.idLocal = '.$idLocal;         
    $stringsql .= " GROUP BY local.idLocal ORDER BY quantidade  DESC";

        
        $sqlLocal = $connection->createCommand($stringsql);      
        $rstlocal = $sqlLocal->queryAll();

         $i=0;
        
        foreach ($rstlocal as $reg):
        $arrayLocal['nome'][$i] = $reg['nomelocal'];
        $arrayLocal['quantidade'][$i] = $reg['quantidade'];
        $i=$i+1;
        endforeach;      

      }
        list ($dia, $mes, $ano) = split ('[/]', $model->dataInicial);
        $dataIni = $ano.'-'.$mes.'-'.$dia;

        list ($dia, $mes, $ano) = split ('[/]', $model->dataFinal);
        $dataFim = $ano.'-'.$mes.'-'.$dia;
        
        return $this->render('graficos', [ 
          'model' => $model,
          'arrayStatus' => $arrayStatus,
          'arrayPeriodo' => $arrayPeriodo,
          'arrayNatureza' => $arrayNatureza,
          'arrayCategoria' =>$arrayCategoria,
          'arrayLocal' =>$arrayLocal,
        ]);        
          
  }    

  public function actionPrintgrafico($tipo,$periodo, $idCategoria, $status, $idNatureza, $idLocal, $dataInicial, $dataFinal, $radiobutton) {

        $model = new Relatorio();

        $model->periodo = $periodo;
        $model->idCategoria = $idCategoria;
        $model->status = $status;
        $model->idNatureza = $idNatureza;
        $model->idLocal = $idLocal;
        $model->dataInicial = $dataInicial;
        $model->dataFinal = $dataFinal;
        $model->radiobutton = $radiobutton;

        list ($dia, $mes, $ano) = split ('[/]', $model->dataInicial);
        $dataIni = $ano.'-'.$mes.'-'.$dia;

        list ($dia, $mes, $ano) = split ('[/]', $model->dataFinal);
        $dataFim = $ano.'-'.$mes.'-'.$dia;


      $connection = \Yii::$app->db;

      $arrayStatus = array(0 => -1);
      $arrayPeriodo = array(0 => -1);
    
    if ($periodo == 0) {
        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 1'; 
     if ($status!=0) $stringsql .= ' AND status = '.$status;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlManha = $connection->createCommand($stringsql);
        $manha = $sqlManha->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 2';
     if ($status!=0) $stringsql .= ' AND status = '.$status;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlTarde = $connection->createCommand($stringsql);
        $tarde = $sqlTarde->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 3';
     if ($status!=0) $stringsql .= ' AND status = '.$status;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlNoite = $connection->createCommand($stringsql);
        $noite = $sqlNoite->queryScalar();

        $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 4';
     if ($status!=0) $stringsql .= ' AND status = '.$status;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';        
        $sqlMadrugada = $connection->createCommand($stringsql);
        $madrugada = $sqlMadrugada->queryScalar();        
      
        $arrayPeriodo = array(0 => $manha, 1 => $tarde, 2 => $noite, 3 => $madrugada );
      }

      if ($status == 0){
  
      $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 1';
     if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';

    $sqlAberto = $connection->createCommand($stringsql);
        $aberto = $sqlAberto->queryScalar();
 
    $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 2';
     if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';
        $sqlSolucionado = $connection->createCommand($stringsql);
        $solucionado = $sqlSolucionado->queryScalar();


    $stringsql = 'SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 3';
     if ($periodo!=0) $stringsql .= ' AND periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND idCategoria = '.$idCategoria;    
     if ($idNatureza!=0) $stringsql .= ' AND idNatureza = '.$idNatureza;
     if ($idLocal!=0) $stringsql .= ' AND idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';           
        $sqlNotSolucionado = $connection->createCommand($stringsql);
        $notsolucionado = $sqlNotSolucionado->queryScalar();
        
        $arrayStatus = array(0 => $aberto, 1=>$solucionado, 2=>$notsolucionado);
        }

        $arrayNatureza['quantidade'][1] = -1;
        if($idNatureza==0){
        $stringsql = "SELECT naturezaocorrencia.Nome as natureza, COUNT(ocorrencia.idOcorrencia) as quantidade
        FROM ocorrencia
        JOIN naturezaocorrencia ON naturezaocorrencia.idNatureza = ocorrencia.idNatureza
        WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
     if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
     if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;     
     if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;    
     if ($idLocal!=0) $stringsql .= ' AND ocorrencia.idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';         
          $stringsql .= " GROUP BY ocorrencia.idNatureza
        ORDER BY quantidade  DESC";

        $sqlNatureza = $connection->createCommand($stringsql);
        $rstnatureza = $sqlNatureza->queryAll();
        
        $i=0;
        
        $arrayNatureza = array('nome' => array(), 'quantidade' => array());
        foreach ($rstnatureza as $reg):
        $arrayNatureza['nome'][$i] = $reg['natureza'];
        $arrayNatureza['quantidade'][$i] = $reg['quantidade'];

        $i=$i+1;
        
        endforeach;
      }

        $arrayCategoria['quantidade'][1] = -1;
      if($idCategoria==0){
       $stringsql = "SELECT categoria.Nome as categoria, COUNT(ocorrencia.idOcorrencia) as quantidade
        FROM ocorrencia
        JOIN categoria ON categoria.idCategoria =   ocorrencia.idCategoria
        WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
     if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
     if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;     
     if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;    
     if ($idLocal!=0) $stringsql .= ' AND ocorrencia.idSubLocal IN (SELECT idSubLocal FROM sublocal WHERE idLocal = '.$idLocal.')';         
    $stringsql .= " GROUP BY ocorrencia.idCategoria ORDER BY quantidade  DESC";


        $sqlCategoria = $connection->createCommand($stringsql);
        $rstcategoria = $sqlCategoria->queryAll();

         $i=0;
        
        $arrayCategoria = array('nome' => array(), 'quantidade' => array());
        
        foreach ($rstcategoria as $reg):
        $arrayCategoria['nome'][$i] = $reg['categoria'];
        $arrayCategoria['quantidade'][$i] = $reg['quantidade'];
        $i=$i+1;
        endforeach;      
      }

        $arrayLocal = array('nome' => array(), 'quantidade' => array());
        $arrayLocal['quantidade'][1] = -1;

      if($idLocal==0){
        $stringsql = "SELECT local.Nome as nomelocal, COUNT(ocorrencia.idOcorrencia) as quantidade
        FROM ocorrencia
        JOIN sublocal ON sublocal.idSubLocal =  ocorrencia.idSubLocal
        JOIN local ON sublocal.idLocal= local.idLocal
        WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'";
     if ($status!=0) $stringsql .= ' AND ocorrencia.status = '.$status;
     if ($periodo!=0) $stringsql .= ' AND ocorrencia.periodo = '.$periodo;
     if ($idCategoria!=0) $stringsql .= ' AND ocorrencia.idCategoria = '.$idCategoria;     
     if ($idNatureza!=0) $stringsql .= ' AND ocorrencia.idNatureza = '.$idNatureza;    
     if ($idLocal!=0) $stringsql .= ' AND local.idLocal = '.$idLocal;         
    $stringsql .= " GROUP BY local.idLocal ORDER BY quantidade  DESC";

        
        $sqlLocal = $connection->createCommand($stringsql);      
        $rstlocal = $sqlLocal->queryAll();

         $i=0;
        
        foreach ($rstlocal as $reg):
        $arrayLocal['nome'][$i] = $reg['nomelocal'];
        $arrayLocal['quantidade'][$i] = $reg['quantidade'];
        $i=$i+1;
        endforeach;      

      }
        list ($dia, $mes, $ano) = split ('[/]', $model->dataInicial);
        $dataIni = $ano.'-'.$mes.'-'.$dia;

        list ($dia, $mes, $ano) = split ('[/]', $model->dataFinal);
        $dataFim = $ano.'-'.$mes.'-'.$dia;
        
        return $this->render('grafico', [ 
          'tipo' => $tipo,
          'model' => $model,
          'arrayStatus' => $arrayStatus,
          'arrayPeriodo' => $arrayPeriodo,
          'arrayNatureza' => $arrayNatureza,
          'arrayCategoria' =>$arrayCategoria,
          'arrayLocal' =>$arrayLocal,
        ]);        
          
  }

    public function actionRelatorio()
    {

        $model = new Relatorio();

        if ($model->load(Yii::$app->request->post())) {

        if ($model->validatedata() == false) return $this->render('relatorio', [
                'model' => $model,
            ]);

 /*       if($model->mesAno!=null) {

          list ($mes, $ano) = split ('[-]', $model->mesAno);
          $mes = $this->findMonthsNumber($mes);
            
          if ($mes < 10) {
            $model->dataInicial = $ano."-0".$mes."-01";
            $model->dataFinal = $ano."-0".$mes."-31";
          } else {
            $model->dataInicial = $ano."-".$mes."-01";
            $model->dataFinal = $ano."-".$mes."-31";
          }
        }

        if ($model->save()) {*/
          $searchModel = new OcorrenciaSearch();
        
          if($model->idLocal!=0)
          $params['idLocal'] = $model->idLocal;

          if($model->idCategoria!=0)
          $params['idCategoria'] = $model->idCategoria;

          if($model->idNatureza!=0)
          $params['idNatureza'] = $model->idNatureza;
        
          if($model->status!=0)
          $params['status'] = $model->status;

          if($model->periodo!=0)
          $params['periodo'] = $model->periodo;

          if($model->mesAno==null) {
            $params['dataInicial'] = $model->dataInicial;
            $params['dataFinal'] = $model->dataFinal;
          } else {
            list ($mes, $ano) = split ('[-]', $model->mesAno);
            $mes = $this->findMonthsNumber($mes);
            
            if ($mes < 10) {
              $model->dataInicial = "01/"."0".$mes."/".$ano;
              $model->dataFinal = "31/"."0".$mes."/".$ano;

              $params['dataInicial'] = "01/"."0".$mes."/".$ano;
              $params['dataFinal'] = "31/"."0".$mes."/".$ano;
             } else {
              $model->dataInicial = "01/".$mes."/".$ano;
              $model->dataFinal = "31/".$mes."/".$ano;

              $params['dataInicial'] = "01/".$mes."/".$ano;
              $params['dataFinal'] = "31/".$mes."/".$ano;
            }          
          }

        $dataProvider = $searchModel->relatorio(['OcorrenciaSearch' => $params]);

//                return $this->redirect(['view', 'id' => $model->idOcorrencia]);
        return $this->render('indexrelatorio', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
      
        ]);
      } else {
            return $this->render('relatorio', [
                'model' => $model,
            ]);
        }
    }
}
