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
                'only' => ['create', 'index', 'update', 'emaberto', 'relatorio', 'printocorrencia'],
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'update', 'emaberto', 'relatorio', 'printocorrencia'],
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
          <h2> 10. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 11. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
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
          <h2> 10. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 11. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
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
          <h2> 10. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 11. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
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
          <h2> 10. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
          <h2> 11. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
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
        	<h2> 10. Descrição:</h2>  <pre><p>".$model->descricao. "</p></pre>
        	<h2> 11. Procedimento:</h2> <pre><p>".$model->procedimento. "</p></pre> 
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

    public function actionRelatorio()
    {
        $model = new Relatorio();


        if ($model->load(Yii::$app->request->post())) {

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

	if ($model->radiobutton==1) {
      	  if ($model->mes < 10) {
      	 	$dataIni = $model->ano."-0".$model->mes."-01";
      	  	$dataFim = $model->ano."-0".$model->mes."-31";
      	} else {
     	 	$dataIni = $model->ano."-".$model->mes."-01";
      	  	$dataFim = $model->ano."-".$model->mes."-31";

      	}

      	//TABELA OCORRENCIA
      	  $tabelaOco .= "<table border='1' width='1000' align='center' id='tabelaocorrencia'>
      	  <caption>Quadro de ocorrências do mês de ". $this->findMonths($model->mes)." do ano de ".$model->ano."</caption>
           <thead>
           <tr class='header'>
             <td>Número</td>
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
 
        	$ocorrencia = Ocorrencia::find()->where('data >= "'.$dataIni.'"')->andWhere('data <= "'.$dataFim.'"')->all();
			$color  = false;

		 	foreach ($ocorrencia as $reg):
  //          $tabela .= ($color) ? "<tr>" : "<tr class=\'zebra\''>";
		 		$tabelaOco .= "<tr>";
            	$tabelaOco .= "<td>".$reg->idOcorrencia."</td>";
            	$tabelaOco .= "<td>".$reg->idCategoria."</td>";
            	$tabelaOco .= "<td>".$reg->idNatureza."</td>";
            	$tabelaOco .= "<td>".$reg->idLocal."</td>";
            	$tabelaOco .= "<td>".$reg->idSubLocal."</td>";
            	$tabelaOco .= "<td>".$reg->status."</td>";
           		$tabelaOco .= "<td>".$reg->periodo."</td>";
            	$tabelaOco .= "<td>".$reg->data."</td>";
            	$tabelaOco .= "<tr>";
     //       $color = !$color;
        	endforeach;

       		$tabelaOco .= "</table>";

       		//TABELA PERIODO
       		$tabelaPeriodo .= "<table border='1' width='1000' align='center' id='tabelaperiodo'>
      	  <caption>Quantidade de ocorrências por período do mês de ". $this->findMonths($model->mes)." do ano de ".$model->ano."</caption>
           <thead>
           <tr class='header'>
             <td>Manhã</td>
             <td>Tarde</td>
             <td>Noite</td>
             <td>Madrugada</td>
           </tr>
           </thead>
           ";


	  	$connection = \Yii::$app->db;
        $sqlManha = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 1');
        $manha = $sqlManha->queryScalar();
 
        $sqlTarde = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 2');
        $tarde = $sqlTarde->queryScalar();

        $sqlNoite = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 3');
        $noite = $sqlNoite->queryScalar();

        $sqlMadrugada = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 4');
        $madrugada = $sqlMadrugada->queryScalar();        

		 		$tabelaPeriodo .= "<tr>";
            	$tabelaPeriodo .= "<td>".$manha."</td>";
            	$tabelaPeriodo .= "<td>".$tarde."</td>";
            	$tabelaPeriodo .= "<td>".$noite."</td>";
            	$tabelaPeriodo .= "<td>".$madrugada."</td>";
            	$tabelaPeriodo .= "<tr>";
     //       $color = !$color;

       		$tabelaPeriodo .= "</table>";

       		//TABELA STATUS

       		$tabelaStatus .= "<table border='1' width='1000' align='center' id='tabelastatus'>
      	  <caption>Quantidade de ocorrências por status do mês de ". $this->findMonths($model->mes)." do ano de ".$model->ano."</caption>
           <thead>
           <tr class='header'>
             <td>Aberta</td>
             <td>Solucionada</td>
             <td>Não Solucionada</td>
           </tr>
           </thead>
           ";

        $sqlAberto = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 1');
        $aberto = $sqlAberto->queryScalar();
 
        $sqlSolucionado = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 2');
        $solucionado = $sqlSolucionado->queryScalar();

        $sqlNotSolucionado = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 3');
        $notsolcionado = $sqlNotSolucionado->queryScalar();

		 		$tabelaStatus .= "<tr>";
            	$tabelaStatus .= "<td>".$aberto."</td>";
            	$tabelaStatus .= "<td>".$solucionado."</td>";
            	$tabelaStatus .= "<td>".$notsolcionado."</td>";
            	$tabelaStatus .= "<tr>";
     //       $color = !$color;

       		$tabelaStatus .= "</table>";

       		//TABELA NATUREZA
       		$tabelaNatureza .= "<table border='1' width='1000' align='center' id='tabelanatureza'>
      	  <caption>Quantidade de ocorrências por natureza do mês de ". $this->findMonths($model->mes)." do ano de ".$model->ano."</caption>
           <thead>
           <tr class='header'>
             <td>Natureza</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";

        $sql = "SELECT naturezaocorrencia.Nome as natureza, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN naturezaocorrencia ON naturezaocorrencia.idNatureza = ocorrencia.idNatureza
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."' 
				GROUP BY ocorrencia.idNatureza
				ORDER BY quantidade  DESC";

        $sqlNatureza = $connection->createCommand($sql);
        $rstnatureza = $sqlNatureza->queryAll();

        foreach ($rstnatureza as $reg):
		 	$tabelaNatureza .= "<tr>";
            $tabelaNatureza .= "<td>{$reg['natureza']}</td>";
            $tabelaNatureza .= "<td>{$reg['quantidade']}</td>";
            $tabelaNatureza .= "</tr>";
        endforeach;

       		$tabelaNatureza .= "</table>";


     		//TABELA CATEGORIA
       		$tabelaCategoria .= "<table border='1' width='1000' align='center' id='tabelacategoria'>
      	  <caption>Quantidade de ocorrências por categoria do mês de ". $this->findMonths($model->mes)." do ano de ".$model->ano."</caption>
           <thead>
           <tr class='header'>
             <td>Categoria</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";

        $sql = "SELECT categoria.Nome as categoria, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN categoria ON categoria.idCategoria = 	ocorrencia.idCategoria
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."' 
				GROUP BY ocorrencia.idCategoria
				ORDER BY quantidade  DESC";
        
        $sqlCategoria = $connection->createCommand($sql);
        $rstcategoria = $sqlCategoria->queryAll();

        foreach ($rstcategoria as $reg):
		 	$tabelaCategoria .= "<tr>";
            $tabelaCategoria .= "<td>{$reg['categoria']}</td>";
            $tabelaCategoria .= "<td>{$reg['quantidade']}</td>";
            $tabelaCategoria .= "</tr>";
        endforeach;

       		$tabelaCategoria .= "</table>";

     		//TABELA LOCAL
       		$tabelaLocal .= "<table border='1' width='1000' align='center' id='tabelalocal'>
      	  <caption>Quantidade de ocorrências por local do mês de ". $this->findMonths($model->mes)." do ano de ".$model->ano."</caption>
           <thead>
           <tr class='header'>
             <td>Local</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";

        $sql = "SELECT local.Nome as nomelocal, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN sublocal ON sublocal.idSubLocal = 	ocorrencia.idSubLocal
				JOIN local ON sublocal.idLocal= local.idLocal
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'
				GROUP BY local.idLocal
				ORDER BY quantidade  DESC
        		";
        
        $sqlLocal = $connection->createCommand($sql);
        $rstlocal = $sqlLocal->queryAll();

        foreach ($rstlocal as $reg):
		 	$tabelaLocal .= "<tr>";
            $tabelaLocal .= "<td>{$reg['nomelocal']}</td>";
            $tabelaLocal .= "<td>{$reg['quantidade']}</td>";
            $tabelaLocal .= "</tr>";
        endforeach;

       		$tabelaLocal .= "</table>";



	  	} else {

	  	$dataIni = $model->dataInicial;
      	$dataFim = $model->dataFinal;
      	
      	//TABELA OCORRENCIA
      	  $tabelaOco .= "<table border='1' width='1000' align='center' id='tabelaocorrencia'>
      	  <caption>Quadro de ocorrências do período de ". $dataIni." a ".$dataFim."</caption>
           <thead>
           <tr class='header'>
             <td>Número</td>
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
 
        	$ocorrencia = Ocorrencia::find()->where('data >= "'.$dataIni.'"')->andWhere('data <= "'.$dataFim.'"')->all();
			$color  = false;

		 	foreach ($ocorrencia as $reg):
  //          $tabela .= ($color) ? "<tr>" : "<tr class=\'zebra\''>";
		 		$tabelaOco .= "<tr>";
            	$tabelaOco .= "<td>".$reg->idOcorrencia."</td>";
            	$tabelaOco .= "<td>".$reg->idCategoria."</td>";
            	$tabelaOco .= "<td>".$reg->idNatureza."</td>";
            	$tabelaOco .= "<td>".$reg->idLocal."</td>";
            	$tabelaOco .= "<td>".$reg->idSubLocal."</td>";
            	$tabelaOco .= "<td>".$reg->status."</td>";
           		$tabelaOco .= "<td>".$reg->periodo."</td>";
            	$tabelaOco .= "<td>".$reg->data."</td>";
            	$tabelaOco .= "<tr>";
     //       $color = !$color;
        	endforeach;

       		$tabelaOco .= "</table>";

       		//TABELA PERIODO
       		$tabelaPeriodo .= "<table border='1' width='1000' align='center' id='tabelaperiodo'>
      	  <caption>Quantidade de ocorrências por período do mês de ". $dataIni." a ".$dataFim."</caption>
           <thead>
           <tr class='header'>
             <td>Manhã</td>
             <td>Tarde</td>
             <td>Noite</td>
             <td>Madrugada</td>
           </tr>
           </thead>
           ";


	  	$connection = \Yii::$app->db;
        $sqlManha = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 1');
        $manha = $sqlManha->queryScalar();
 
        $sqlTarde = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 2');
        $tarde = $sqlTarde->queryScalar();

        $sqlNoite = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 3');
        $noite = $sqlNoite->queryScalar();

        $sqlMadrugada = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND periodo = 4');
        $madrugada = $sqlMadrugada->queryScalar();        

		 		$tabelaPeriodo .= "<tr>";
            	$tabelaPeriodo .= "<td>".$manha."</td>";
            	$tabelaPeriodo .= "<td>".$tarde."</td>";
            	$tabelaPeriodo .= "<td>".$noite."</td>";
            	$tabelaPeriodo .= "<td>".$madrugada."</td>";
            	$tabelaPeriodo .= "<tr>";
     //       $color = !$color;

       		$tabelaPeriodo .= "</table>";

       		//TABELA STATUS

       		$tabelaStatus .= "<table border='1' width='1000' align='center' id='tabelastatus'>
      	  <caption>Quantidade de ocorrências por status do mês de ". $dataIni." a ".$dataFim."</caption>
           <thead>
           <tr class='header'>
             <td>Aberta</td>
             <td>Solucionada</td>
             <td>Não Solucionada</td>
           </tr>
           </thead>
           ";

        $sqlAberto = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 1');
        $aberto = $sqlAberto->queryScalar();
 
        $sqlSolucionado = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 2');
        $solucionado = $sqlSolucionado->queryScalar();

        $sqlNotSolucionado = $connection->createCommand('SELECT COUNT(idOcorrencia) as cont FROM ocorrencia WHERE data >= "'.$dataIni.'" AND data <="'.$dataFim.'" AND status = 3');
        $notsolcionado = $sqlNotSolucionado->queryScalar();

		 		$tabelaStatus .= "<tr>";
            	$tabelaStatus .= "<td>".$aberto."</td>";
            	$tabelaStatus .= "<td>".$solucionado."</td>";
            	$tabelaStatus .= "<td>".$notsolcionado."</td>";
            	$tabelaStatus .= "<tr>";
     //       $color = !$color;

       		$tabelaStatus .= "</table>";

       		//TABELA NATUREZA
       		$tabelaNatureza .= "<table border='1' width='1000' align='center' id='tabelanatureza'>
      	  <caption>Quantidade de ocorrências por natureza do mês de ". $dataIni." a ".$dataFim."</caption>
           <thead>
           <tr class='header'>
             <td>Natureza</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";

        $sql = "SELECT naturezaocorrencia.Nome as natureza, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN naturezaocorrencia ON naturezaocorrencia.idNatureza = ocorrencia.idNatureza
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."' 
				GROUP BY ocorrencia.idNatureza
				ORDER BY quantidade  DESC";

        $sqlNatureza = $connection->createCommand($sql);
        $rstnatureza = $sqlNatureza->queryAll();

        foreach ($rstnatureza as $reg):
		 	$tabelaNatureza .= "<tr>";
            $tabelaNatureza .= "<td>{$reg['natureza']}</td>";
            $tabelaNatureza .= "<td>{$reg['quantidade']}</td>";
            $tabelaNatureza .= "</tr>";
        endforeach;

       		$tabelaNatureza .= "</table>";


     		//TABELA CATEGORIA
       		$tabelaCategoria .= "<table border='1' width='1000' align='center' id='tabelacategoria'>
      	  <caption>Quantidade de ocorrências por categoria do mês de ". $dataIni." a ".$dataFim."</caption>
           <thead>
           <tr class='header'>
             <td>Categoria</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";

        $sql = "SELECT categoria.Nome as categoria, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN categoria ON categoria.idCategoria = 	ocorrencia.idCategoria
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."' 
				GROUP BY ocorrencia.idCategoria
				ORDER BY quantidade  DESC";
        
        $sqlCategoria = $connection->createCommand($sql);
        $rstcategoria = $sqlCategoria->queryAll();

        foreach ($rstcategoria as $reg):
		 	$tabelaCategoria .= "<tr>";
            $tabelaCategoria .= "<td>{$reg['categoria']}</td>";
            $tabelaCategoria .= "<td>{$reg['quantidade']}</td>";
            $tabelaCategoria .= "</tr>";
        endforeach;

       		$tabelaCategoria .= "</table>";

     		//TABELA LOCAL
       		$tabelaLocal .= "<table border='1' width='1000' align='center' id='tabelalocal'>
      	  <caption>Quantidade de ocorrências por local do mês de ". $dataIni." a ".$dataFim."</caption>
           <thead>
           <tr class='header'>
             <td>Local</td>
             <td>Quantidade</td>
           </tr>
           </thead>
           ";

        $sql = "SELECT local.Nome as nomelocal, COUNT(ocorrencia.idOcorrencia) as quantidade
				FROM ocorrencia
				JOIN sublocal ON sublocal.idSubLocal = 	ocorrencia.idSubLocal
				JOIN local ON sublocal.idLocal= local.idLocal
				WHERE ocorrencia.data >= '".$dataIni."' AND ocorrencia.data <= '".$dataFim."'
				GROUP BY local.idLocal
				ORDER BY quantidade  DESC
        		";
        
        $sqlLocal = $connection->createCommand($sql);
        $rstlocal = $sqlLocal->queryAll();

        foreach ($rstlocal as $reg):
		 	$tabelaLocal .= "<tr>";
            $tabelaLocal .= "<td>{$reg['nomelocal']}</td>";
            $tabelaLocal .= "<td>{$reg['quantidade']}</td>";
            $tabelaLocal .= "</tr>";
        endforeach;

       		$tabelaLocal .= "</table>";



	  	}


        $total = Ocorrencia::find()->where('data >= "'.$dataIni.'"')->andWhere('data <= "'.$dataFim.'"')->count();
      	  
      	  $html = "
        	<img id='cabecalho' src='./../views/ocorrencia/relatorio/figura.png'/>
     		<span id='data'><b>Gerado em: ".$date."</b></span>
     		<h3>Total de ocorrências no período selecionado: ".$total."</h3>    		
	        ".$tabelaOco."
	        ".$tabelaPeriodo."
	        ".$tabelaStatus."
	        ".$tabelaNatureza."	        
	        ".$tabelaCategoria."
	        ".$tabelaLocal."	        
	        ";

		$mpdf->WriteHTML($html);
		$mpdf->Output();
        exit;
         
        } else {
            return $this->render('relatorio', [
                'model' => $model,
            ]);
        }
    }
  }
