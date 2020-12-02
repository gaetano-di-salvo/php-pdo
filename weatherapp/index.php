<?php

try
{
  echo 'connection db...';
  echo nl2br("\n");
	// On se connecte à MySQL
  $pdo = new PDO('mysql:host=localhost;dbname=weatherapp;charset=utf8','phpmyadmin','test');

  // for error handling in the try and catch
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
}
catch(Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}

echo 'GET msg err : ';
echo $_GET['err'];
echo nl2br("\n");

if ( (isset($_POST[ville])) && (isset($_POST[haut])) && (isset($_POST[bas])) ){
  echo 'le formulaire est soumis par POST';
  echo nl2br("\n");
  // sanitize
  $ville = filter_var($_POST["ville"], FILTER_SANITIZE_STRING);
  $haut = filter_var($_POST["haut"], FILTER_SANITIZE_STRING);
  $bas = filter_var($_POST["bas"], FILTER_SANITIZE_STRING);
  
  // validate


  // insert into table
  echo 'Nous allons insérer les valeurs suivantes : ';
  echo 'ville: '.$ville;
  echo nl2br("\n");
  echo 'haut: '.$haut;
  echo nl2br("\n");
  echo 'bas: '.$bas;
  echo nl2br("\n");

  $sql = "INSERT INTO `Météo` (ville, haut, bas) 
  VALUES (:ville, :haut, :bas)";

  $stmt = $pdo->prepare($sql);

  try {
  $stmt->execute(array('ville' => $ville, 'haut' => $haut, 'bas' => $bas));
  } catch (PDOException $e) {
    //Do your error handling here
    $message = $e->getMessage();
    echo 'Insert Error, check the validity of your data.';    
    //header('Location: '.$_SERVER['PHP_SELF']);
    //$_POST = array();
    //header('Location: index.php?error='.$err);
  }

} else {
  echo "le formulaire n'est pas encore soumis";
  echo nl2br("\n");  
}

// requête de sélection
$resultat = $pdo->query('SELECT * FROM `Météo`');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>weatherapp</title>    
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="center">
    <table>
      <thead>
        <tr>
          <th>Ville</th>
          <th>Haut</th>
          <th>Bas</th>
        </tr>
      </thead>
      <tbody>

      <?php
        while ($donnees = $resultat->fetch())
        {
          echo '<tr>';
          echo '<td>'.$donnees['ville'].'</td>';
          echo '<td>'.$donnees['haut'].'</td>';
          echo '<td>'.$donnees['bas'].'</td>';
          echo '</tr>';
        }

        $resultat->closeCursor();
      ?>

      </tbody>
    </table>
  </div>
  
  <div class="center_form">
    <p>Ajouter une ville et ses températures</p>
    <form action="index.php" method="post">
      <label for="ville">Ville:</label><br>
      <input type="text" id="ville" name="ville" value=""><br>

      <label for="bas">bas:</label><br>
      <input type="text" id="bas" name="bas" value=""><br>
      
      <label for="haut">Haut:</label><br>
      <input type="text" id="haut" name="haut" value=""><br><br>
      
      <input type="submit" value="submit">
    </form> 
  </div>
</body>
</html>


