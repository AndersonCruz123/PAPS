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
use app\models\LocalSearch;
use app\models\SublocalSearch;
//use app\controllers\Sublocal;
use app\models\Local;
use app\models\Sublocal;
use mPDF;
use yii\db\Query;

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
                        'actions' => ['index','view', 'update','delete', 'naoverificadas', 'printdenuncia'],
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

    public function actionPrintdenuncia($id)
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
    
        $foto = FotoController::getFotoDenuncia($model->idDenuncia);
        if ($foto != null) {
            $model->comentarioFoto = $foto[0]->comentario;
            $model->fotos = $foto;
        }

        $tam = sizeof($model->fotos);
        $date = date("d/m/Y H:i:s ");

        if ($tam==0) {
          $html = "
            <img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
            <span id='data'><b>Gerado em: ".$date."</b></span> 
            <h2> 1. Número da Denuncia: ".$model->idDenuncia. "</h2>
            <h2> 2. Status:</h2> <p>".$model->status. "</p>
            <h2> 3. Data do acontecimento da denúncia:</h2> <p>".$model->data. "</p>
            <h2> 4. Hora do acontecimento da denúncia:</h2> <p>".$model->hora. "</p>
            <h2> 5. Período do acontecimento da denúncia:</h2> <p>".$model->periodo. "</p>            
            <h2> 6. Local:</h2> <p>".$model->idLocal. "</p>
            <h2> 7. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
            <h2> 8. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 9. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
            ";
        }

        if ($tam==1) {
          $html = "
            <img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
            <span id='data'><b>Gerado em: ".$date."</b></span> 
            <h2> 1. Número da Denuncia: ".$model->idDenuncia. "</h2>
            <h2> 2. Status:</h2> <p>".$model->status. "</p>
            <h2> 3. Data do acontecimento da denúncia:</h2> <p>".$model->data. "</p>
            <h2> 4. Hora do acontecimento da denúncia:</h2> <p>".$model->hora. "</p>
            <h2> 5. Período do acontecimento da denúncia:</h2> <p>".$model->periodo. "</p>            
            <h2> 6. Local:</h2> <p>".$model->idLocal. "</p>
            <h2> 7. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
            <h2> 8. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 9. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>            
            <h2> 10. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
          <h2> 11. Foto:</h2>
            <img id='foto1' src='./../web/uploadFoto/".$model->fotos[0]->nome."' alt='".$model->fotos[0]->nome."'/>
            "           
            ;
        }

        else if ($tam==2) {
          $html = "
            <img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
            <span id='data'><b>Gerado em: ".$date."</b></span> 
            <h2> 1. Número da Denuncia: ".$model->idDenuncia. "</h2>
            <h2> 2. Status:</h2> <p>".$model->status. "</p>
            <h2> 3. Data do acontecimento da denúncia:</h2> <p>".$model->data. "</p>
            <h2> 4. Hora do acontecimento da denúncia:</h2> <p>".$model->hora. "</p>
            <h2> 5. Período do acontecimento da denúncia:</h2> <p>".$model->periodo. "</p>            
            <h2> 6. Local:</h2> <p>".$model->idLocal. "</p>
            <h2> 7. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
            <h2> 8. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 9. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>            
            <h2> 10. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
          <h2> 11. Foto:</h2>
            <img id='foto1' src='./../web/uploadFoto/".$model->fotos[0]->nome."' alt='".$model->fotos[0]->nome."'/>
            <img id='foto2' src='./../web/uploadFoto/".$model->fotos[1]->nome."' alt='".$model->fotos[1]->nome."'/>
            "           
            ;
        }

        else if ($tam==3) {
          $html = "
            <img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
            <span id='data'><b>Gerado em: ".$date."</b></span> 
            <h2> 1. Número da Denuncia: ".$model->idDenuncia. "</h2>
            <h2> 2. Status:</h2> <p>".$model->status. "</p>
            <h2> 3. Data do acontecimento da denúncia:</h2> <p>".$model->data. "</p>
            <h2> 4. Hora do acontecimento da denúncia:</h2> <p>".$model->hora. "</p>
            <h2> 5. Período do acontecimento da denúncia:</h2> <p>".$model->periodo. "</p>            
            <h2> 6. Local:</h2> <p>".$model->idLocal. "</p>
            <h2> 7. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
            <h2> 8. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 9. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>            
            <h2> 10. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
          <h2> 11. Foto:</h2>
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
            <h2> 1. Número da Denuncia: ".$model->idDenuncia. "</h2>
            <h2> 2. Status:</h2> <p>".$model->status. "</p>
            <h2> 3. Data do acontecimento da denúncia:</h2> <p>".$model->data. "</p>
            <h2> 4. Hora do acontecimento da denúncia:</h2> <p>".$model->hora. "</p>
            <h2> 5. Período do acontecimento da denúncia:</h2> <p>".$model->periodo. "</p>            
            <h2> 6. Local:</h2> <p>".$model->idLocal. "</p>
            <h2> 7. Sublocal:</h2> <p>".$model->idSubLocal. "</p>
            <h2> 8. Detalhamento do local:</h2> <p>".$model->detalheLocal. "</p>
          <h2> 9. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>            
            <h2> 10. Comentário sobre as fotos:</h2> <p>".$model->comentarioFoto. "</p>
          <h2> 11. Foto:</h2>
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

    /**
     * Creates a new Denuncia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Denuncia();
        $model->idLocal = 0;

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
                    $foto->comentario = $model->comentarioFoto;                     
                    $file->saveAs( $foto->endereco);
                    
                    $foto->save();

                    $foto = null;
                    }
                
                return $this->render('sucesso');
            } else {
                list ($ano, $mes, $dia) = split ('[-]', $model->data);
                $model->data = $dia.'/'.$mes.'/'.$ano;              
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

        $model->idSubLocal = $model->idSubLocalbkp;
        $sublocal = Sublocal::findOne($model->idSubLocalbkp);
        $model->idLocal = $sublocal->idLocalbkp;


        if ($model->load(Yii::$app->request->post())){

         list ($dia, $mes, $ano) = split ('[/]', $model->data);
        $model->data = $ano.'-'.$mes.'-'.$dia;

            if($model->save()) {
                if($model->status == 2) {
                    return $this->redirect(['ocorrencia/createfromdenuncia', 'idDenuncia' => $model->idDenuncia]);
                }    
                return $this->redirect(['view', 'id' => $model->idDenuncia]);                    
            }
        else {
                list ($ano, $mes, $dia) = split ('[-]', $model->data);
                $model->data = $dia.'/'.$mes.'/'.$ano;
                $model->descricao = "aquiveadinho";
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
