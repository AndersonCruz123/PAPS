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
   

    <title>Denuncia Sucessso</title>
  <!-- Bootstrap core CSS -->
    <link href="bootstrap.css" rel="stylesheet">

          <style> 
          .navbar
          {
            background-color: #EEE9E9;
            border-color: transparent;
            }
           
           #policial{
              height: 250px;
              width: 250px;
              position: absolute; 
             margin-left: 300px;
              margin-top:90px; 
            } 

            #chat{
              height: 250px;
              width: 250px;
              position: absolute; 
             margin-left: 500px;
              margin-top:50px; 
            } 
  


        </style>
  </head>

  <body>
    <img id="policial" src="policial.jpg">
    <img id="chat" src="chat.png">
    

  </body>

