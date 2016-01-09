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
   

    <title>Home 1</title>

    <!-- Bootstrap core CSS -->
    <link href='bootstrap.css' rel='stylesheet'>


          <style> 
          .navbar
          {
            background-color: #EEE9E9;
            border-color: transparent;
            }  
       
              .container2{
              height: 250px;
              width: 250px;
              margin-left: 250px;
              margin-top:30px;            
              padding: 25px;
             -webkit-border-radius: 15px;
             -moz-border-radius: 15px;
             border-radius: 15px;
             -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
             border-radius: 8px;
             -moz-background-clip: padding;
             -webkit-background-clip: padding-box;
             background-color: #2E8B57;
             border: solid 2px white;
             padding: 15px;
            }

            .container3{
              height: 250px;
              width: 250px;
              margin-left: 600px;
              margin-top:-252px;            
              padding: 25px;
             -webkit-border-radius: 15px;
             -moz-border-radius: 15px;
             border-radius: 15px;
             -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
             border-radius: 8px;
             -moz-background-clip: padding;
             -webkit-background-clip: padding-box;
             background-color: #2E8B57;
             border: solid 2px white;
             padding: 15px;
            }


       
             #teste2{
              height: 90px;
              width: 330px;
              position: absolute; 
             margin-left: 485px;
              margin-top:100px;    

            }
              #teste3{
              height: 90px;
              width: 310px;
              position: absolute; 
             margin-left: 137px;
              margin-top:105px;  


            }    
            
             #titulo{
              font-size:30px;line-height:1em;margin:4% 0; color: #2E8B57; margin-left: 120px;
              font-family:'DejaVu Serif';

              
             }  

             h1{
              color: white;
              font-size: 90px;
              font-family: bold;
              margin-left: 80px;
              font-family:'Microsoft Yi Baite';
             }
           
        </style>
  </head>
<body>
 <h1 id='titulo'><p> Monitoramento de ocorrências e denúncias em tempo real</p></h1>        
                 
    <div class='container2'> 
   <a href='index.php?r=ocorrencia%2Femaberto'> <img id='teste2' src='aberto.png' id='ocorrencia'> </a>
    </br></br></br>

  <a href='index.php?r=denuncia%2Fnaoverificadas'><h1 id=totalden>".$totalDen."</h1></a>

    </div>
                  
    <div class='container3' id='denuncia'> 
   <a href='index.php?r=denuncia%2Fnaoverificadas'><img id='teste3' src='naoverificada.png'></a>
    </br></br></br>
 
 <a href='index.php?r=ocorrencia%2Femaberto'><h1 id=totalden>".$total."</h1></a>
 

    </div>          
 
  <script>

     console.log('passei aqui');
    

  </script>
              
</body>
</html>";
    }  elseif(Yii::$app->user->isGuest == false && Yii::$app->user->identity->idTipoUsuario == 'Segurança Terceirizada') {
     $total = Denuncia::find()->where('status = 1')->count();
 echo "<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content=''>
    <meta name='author' content=''>
   

    <title>Home 1</title>

    <!-- Bootstrap core CSS -->
    <link href='bootstrap.css' rel='stylesheet'>


          <style> 
          .navbar
          {
            background-color: #EEE9E9;
            border-color: transparent;
            }  
       
              .container2{
              height: 250px;
              width: 250px;
              margin-left: 330px;
              margin-top:30px;            
              padding: 25px;
             -webkit-border-radius: 15px;
             -moz-border-radius: 15px;
             border-radius: 15px;
             -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
             border-radius: 8px;
             -moz-background-clip: padding;
             -webkit-background-clip: padding-box;
             background-color: #2E8B57;
             border: solid 2px white;
             padding: 15px;
            }

            .container3{
              height: 250px;
              width: 250px;
              margin-left: 600px;
              margin-top:-252px;            
              padding: 25px;
             -webkit-border-radius: 15px;
             -moz-border-radius: 15px;
             border-radius: 15px;
             -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
             border-radius: 8px;
             -moz-background-clip: padding;
             -webkit-background-clip: padding-box;
             background-color: #2E8B57;
             border: solid 2px white;
             padding: 15px;
            }


       
             #teste2{
              height: 90px;
              width: 330px;
              position: absolute; 
             margin-left: 530px;
              margin-top:100px;    

            }
              #teste3{
              height: 90px;
              width: 310px;
              position: absolute; 
             margin-left: 217px;
              margin-top:105px;  


            }    
            
             #titulo{
              font-size:30px;line-height:1em;margin:4% 0; color: #2E8B57; margin-left: 170px;
              font-family:'DejaVu Serif';

              
             }  

             h1{
              color: white;
              font-size: 90px;
              font-family: bold;
              margin-left: 80px;
              font-family:'Microsoft Yi Baite';
             }
           
        </style>
  </head>




<body>


 <h1 id='titulo'><p> Monitoramento de denúncias em tempo real</p></h1> 

             
                                   
    <div class='container2' id='denuncia'> 
   <a href='index.php?r=denuncia%2Fnaoverificadas'> <img id='teste3' src='naoverificada.png'></a>
    </br></br></br>      

    <a href='index.php?r=denuncia%2Fnaoverificadas'><h1 id=totalden>".$total."</h1></a>

    </div>          
 
  <script>

     console.log('passei aqui');
    

  </script>
              
</body>
</html>";

}

    else {

 echo "<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='description' content=''>
    <meta name='author' content=''>
   

    <title>Home 1</title>

    <!-- Bootstrap core CSS -->
    <link href='bootstrap.css' rel='stylesheet'>


          <style> 
          .navbar
          {
            background-color: #EEE9E9;
            border-color: transparent;
            }  

        
            .container1{
              height: 430px;
              width: 500px;
              margin-left: 330px;
              margin-bottom: 250px;            
              padding: 25px;
             -webkit-border-radius: 15px;
             -moz-border-radius: 15px;
             border-radius: 15px;
             -moz-border-radius: 8px;
            -webkit-border-radius: 8px;
             border-radius: 8px;
             -moz-background-clip: padding;
             -webkit-background-clip: padding-box;
             background-color: white;
             border: solid 2px white;
             padding: 15px;
            }
            #teste{
              width: 320px;
              height: 250px;
              position: absolute; 
              margin-left: 330px; /* posiciona a 90px para a esquerda */ 
              top: 130px; /* posiciona a 70px para baixo */

            }

             #teste1{
              width: 350px;
              height: 90px;
              position: absolute; 
              margin-left: 330px; /* posiciona a 90px para a esquerda */ 
              top: 390px; /* posiciona a 70px para baixo */

            }

             #teste2{
              width: 1390px;
              height: 70px;
              position: absolute; 
              margin-left: -200px; /* posiciona a 90px para a esquerda */ 
              top: 755px; /* posiciona a 70px para baixo */

            }

             #teste3{
              width: 100px;
              height: 60px;
              position: absolute; 
              margin-left: 1050px; /* posiciona a 90px para a esquerda */ 
              top: 759px; /* posiciona a 70px para baixo */
            }


           
           
        </style>
  </head>


<body>
  <div class='site-index'>    
            <div class='container1'> 

              <!--  <p><a class='btn btn-primary btn-lg' href='#' role='button'>Denunciar</a></p> -->
               <img id='teste' src='5.png'>
               <a href='index.php?r=denuncia%2Fcreate'><img id='teste1' src='6.png' ></a>
               <img id='teste2' src='fim.png'>
               <img id='teste3' src='icomp.png'>
               </div>      
              
</body>
</html>";
}
