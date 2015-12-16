<?php

/* @var $this yii\web\View */

use app\models\Ocorrencia;
use app\models\Denuncia;

$this->title = 'SOS UFAM';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
   

    <title>Home 1</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap.css" rel="stylesheet">


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
             margin-left: 220px;
              margin-top:105px;  


            }    
            
             #titulo{
              font-size:30px;line-height:1em;margin:4% 0; color: #2E8B57; margin-left: 170px;
              font-family:"DejaVu Serif";

              
             }  

             h1{
              color: white;
              font-size: 90px;
              font-family: bold;
              margin-left: 80px;
              font-family:"Microsoft Yi Baite";
             }
           
        </style>
  </head>




<body>


 <h1 id="titulo"><p> Monitoramento de denúncias em tempo real</p></h1> 

             
                                   
    <div class="container2" id="denuncia"> 
    <img id="teste3" src="naoverificada.png">
    </br></br></br>      
    <?php
     $total = Denuncia::find()->where('status = 1')->count();
    echo "<a href='index.php?r=denuncia%2Fnaoverificadas'><h1 id=totalden>".$total."</h1></a>";
    ?>

    </div>          
 
  <script>

     console.log("passei aqui");
    

  </script>
              
</body>
</html>

