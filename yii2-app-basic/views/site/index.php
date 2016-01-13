<?php

use app\assets\AppAsset;
use app\models\Ocorrencia;
use app\models\Denuncia;

AppAsset::register($this);

/* @var $this yii\web\View */

$this->title = 'SOS-UFAM';

      if(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Chefe de Segurança') {
     $totalDen = Denuncia::find()->where('status = 1')->count();
    $total = Ocorrencia::find()->where('status = 1')->count();

echo "<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content=''>
    <meta name='author' content=''>
   

    <title>Home</title>

    <!-- Bootstrap core CSS -->
    <link href='bootstrap.css' rel='stylesheet'>


          <style> 
          .navbar
          {
            background-color: #EEE9E9;
            border-color: transparent;
            }  
       
                 
             #teste2{
              max-width:100%; 
             height:auto;
             width: 310px;            
             left:59.2%;
             top: 251px; 
             position: fixed;   

            }
              #teste3{
             max-width:100%; 
             height:auto;
             width: 309px;            
             left:31%;
             top: 250px; 
             position: fixed;

            }    
            
             #titulo{
              font-size:30px;line-height:1em;margin:4% 0; color: #2E8B57; margin-left: 150px;
              font-family:'DejaVu Serif'             
             }  

             h1{
               max-width:100%; 
              height:auto;
              color: white;
              font-size: 90px;
              font-family: bold;
              margin-left: 80px;
              font-family:'Microsoft Yi Baite';
             }
             p{
              max-width:100%; 
             height:auto;
             }

             #totalden{
               max-width:10%; 
              height:auto;
              left: 22%;
              color: white;
              position: relative;

             }

             #totalOcorrencia{
              max-width:10%;
              left: 55%;
              top: 120px;
              color: white;
              position: relative;

             }
  
        </style>
  </head>

<body>
 <h1 id='titulo'><p> Monitoramento de ocorrências e denúncias em tempo real</p></h1> 


<img id='teste2' src='aberto.png' /> <a href='index.php?r=ocorrencia%2Femaberto'><h1 id=totalOcorrencia>".$total."</h1></a>

<img id='teste3' src='naoverificada.png' />      <a href='index.php?r=denuncia%2Fnaoverificadas'><h1 id=totalden>".$totalDen."</h1></a>  

</body>
</html>
";
    }  elseif(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Segurança Terceirizada') {
     $totalDen = Denuncia::find()->where('status = 1')->count();
 echo "<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content=''>
    <meta name='author' content=''>
   

    <title>Home</title>

    <!-- Bootstrap core CSS -->
    <link href='bootstrap.css' rel='stylesheet'>


          <style> 
          .navbar
          {
            background-color: #EEE9E9;
            border-color: transparent;
            }  
       
                 
             #teste2{
              max-width:100%; 
             height:auto;
             width: 310px;            
             left:59.2%;
             top: 251px; 
             position: fixed;   

            }
              #teste3{
             max-width:100%; 
             height:auto;
             width: 309px;            
             left:40%;
             top: 250px; 
             position: fixed;

            }    
            
             #titulo{
              font-size:30px;line-height:1em;margin:4% 0; color: #2E8B57; margin-left: 150px;
              font-family:'DejaVu Serif'             
             }  

             h1{
               max-width:100%; 
              height:auto;
              color: white;
              font-size: 90px;
              font-family: bold;
              margin-left: 80px;
              font-family:'Microsoft Yi Baite';
             }
             p{
              max-width:100%; 
             height:auto;
             }

             #totalden{
             top:120px;
               max-width:10%; 
              height:auto;
              left: 32%;
              color: white;
              position: relative;

             }

             #totalOcorrencia{
              max-width:10%;
              left: 55%;
              top: 120px;
              color: white;
              position: relative;

             }
  
        </style>
  </head>

<body>


 <h1 id='titulo'><p> Monitoramento de ocorrências e denúncias em tempo real</p></h1> 

<div>
  <img id='teste3' src='naoverificada.png' />  <a href='index.php?r=denuncia%2Fnaoverificadas'> <h1 id=totalden>".$totalDen."</h1></a>  
</div>              
</body>
</html>
";

}

    else {

 echo "<html>
<head>
<title>Iniciar</title>
<link rel='stylesheet' href='bootstrap.css'>
<style> 
          .navbar
          {
            background-color: #EEE9E9;
            border-color: transparent;
            } 

            #police
            { 
             max-width:100%; 
             height:auto;
             width: 350px;            
             left:44%;
             top: 200px;    
            }   
            #fazerDenuncia{
             max-width:100%; 
             height:auto; 
             width: 350px;
             left:45%;
             top: 470px; 
            }
              
            
</style>
</head>
<body>
  
    <img id='police' src='5.png'/>
    <a href='index.php?r=denuncia%2Fcreate'><img id='fazerDenuncia' src='6.png'/></a>
  
</body>
</html>";
}
