<?php

try
{  
	// On se connecte à MySQL
  $pdo = new PDO('mysql:host=localhost;dbname=weatherapp;charset=utf8','phpmyadmin','test');

  // for error handling in the try and catch
  // without this line 'INSERT INTO' error will not be triggered in the try and catch
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
}
catch(Exception $e)
{
  // for debug purpose
  // echo $e->getMessage();

  // En cas d'erreur, on affiche un message et on arrête tout
  die('Erreur : Problème de connection à la base de données');
}
