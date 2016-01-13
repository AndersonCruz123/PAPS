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
             max-width:100%; 
             height:auto;
             height: 250px;
             width: 250px;              
             left:40%;
             top: 300px; 
            } 

            #chat{
             max-width:100%; 
             height:auto;
             height: 250px;
             width: 250px;
             position: fixed; 
             left:55%;
             top: 120px;             
            } 
        </style>
  </head>

  <body>
    <img id="policial" src="policial.jpg">
    <img id="chat" src="chat.png">
    

  </body>
