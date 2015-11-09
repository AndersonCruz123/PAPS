<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Navbar Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
   


          <style> 
          .navbar
          {
            background-color: #EEE9E9;
            }

            .jumbotron {
    margin-bottom: 8px;
    height: 420px;
    color: black;
    text-shadow: #DCDCDC 0.4em 0.4em 0.4em;
    background-color: #DCDCDC ;
}

     .jumbotron {
background-image: url("teste.jpg");
background-size: cover;}

            h1 { 
    display: block;
    font-size: 2em;
    margin-top: 0.40em;
    margin-bottom: 0.0em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

         h4 { 
   font-size: 15px;
   margin:-70px 0px;

    }

img {
border: 4px ;
background: transparent;
margin:90px 10px;
}
#esqueciminhasenha {
 margin:10px 10px; 
}
            
        </style>
  </head>

<div class="site-index">

    
            <div class="jumbotron">
         
              <h1 style="text-align:center">Sistema de Ocorrência de Segurança</h1>
             <a id="esqueciminhasenha" href="index.php?r=user%2Fforgot">Esqueci minha senha</a> 
            <img id="icomp"src=icomp.png width="100" height="50" / >
            <img id="ufam" src=ufam.png  width="50" height="50" / >
            <h4>© ICOMP - Instituto de Computação</br>
Desenvolvido no contexto da disciplina ICC410 - 2015/02<h4>
        
        
      </div>

   
</div>
