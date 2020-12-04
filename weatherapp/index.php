<?php

require_once('db-connect.php');

// handle GET result of form POST
// ------------------------------ 

if ( (isset($_GET['result'])) ){
  echo nl2br("\n"); 
  $result = filter_var($_GET['result'], FILTER_SANITIZE_STRING); 
  echo "Result of current process : ".$result;
  echo nl2br("\n"); 
  echo nl2br("\n");  
} else {
  //for debug purpose...
  //echo "GET is not set...";
  //echo nl2br("\n");  
}

// handle form POST delete
// -----------------------

if(isset($_POST['delete'])){
  //echo 'post delete is SET';
  
  $del = $_POST['delete'];
  
  $is_error_del = false;
  echo nl2br("\n");  
  foreach($del as $id){
    //echo $id;
    //echo nl2br("\n");  
    
    // PDO Delete data
    try{
      $count=$pdo->prepare("DELETE FROM Météo WHERE id=:id");
      $count->bindParam(":id",$id,PDO::PARAM_INT);
      $count->execute();
    }
    catch (PDOException $e) {
      // error handling
      $is_error_del = true;
      //$msg = $e->getMessage();        
    }    
  }

  if ($is_error_del){
    $result = 'Delete Error: an error occur while deleting process.';    
  } else {
    $result = 'Delete process ended with success.';    
  }

  // redirect the result info to self page with GET
  header('Location: index.php?result='.$result); 

} else {
  // echo for debug purpose
  //echo 'post delete is NOT set';
}
 

// handle form POST insert
// -----------------------

if ( (isset($_POST['ville'])) && (isset($_POST['haut'])) && (isset($_POST['bas'])) ){
// if ( (isset($_POST['ville']) && !empty(trim($_POST['ville']))) && (isset($_POST['haut']) && !empty(trim($_POST['haut']))) && (isset($_POST['bas']) && !empty(trim($_POST['bas']))) ){
  echo 'le formulaire est soumis par POST';
  echo nl2br("\n");
  // sanitize
  $ville = filter_var($_POST["ville"], FILTER_SANITIZE_STRING);
  $haut = filter_var($_POST["haut"], FILTER_SANITIZE_STRING);
  $bas = filter_var($_POST["bas"], FILTER_SANITIZE_STRING);
  
  // validate

  
  // echo 'Nous allons insérer les valeurs suivantes : ';
  // echo nl2br("\n");
  // echo 'ville: '.$ville;
  // echo nl2br("\n");
  // echo 'haut: '.$haut;
  // echo nl2br("\n");
  // echo 'bas: '.$bas;
  // echo nl2br("\n");


  // insert into table
  // -----------------
  $sql = "INSERT INTO `Météo` (ville, haut, bas) 
  VALUES (:ville, :haut, :bas)";

  $stmt = $pdo->prepare($sql);

  $is_error = false;
  try {
  $stmt->execute(array('ville' => $ville, 'haut' => $haut, 'bas' => $bas));
  } 
  catch (PDOException $e) {
    //Do your error handling here
    $is_error = true;
    //$message = $e->getMessage();        
  }
  
  // result management from error detection
  if ($is_error){
    $result = 'Insert Error, check the validity of your data please! [Ville: '.$ville.'], [Haut: '.$haut.'], [Bas: '.$bas.']';    
  } else {
    $result = 'A New record is well added in the Database';    
  }

  // redirect the result info to self page with header location and GET method directly trow url
  // to ovoid refresh click button problem on self submited page.
  header('Location: index.php?result='.$result);

} else {
  // for debug purpose
  //echo "le formulaire n'est pas encore soumis";
  //echo nl2br("\n");  
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
  <!-- Form self submit for delete handling-->
   <form method='post' action='index.php'>    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Ville</th>
          <th>Haut</th>
          <th>Bas</th>
          <th colspan="2"><input type='submit' value='Delete'></th>
        </tr>
      </thead>
      <tbody>

      <?php
        while ($donnees = $resultat->fetch())
        {
          echo '<tr>';
          echo '<td>'.$donnees['id'].'</td>';
          echo '<td>'.$donnees['ville'].'</td>';
          echo '<td>'.$donnees['haut'].'</td>';
          echo '<td>'.$donnees['bas'].'</td>';
          echo '<td><label><input type="checkbox" name="delete[]" value="'.$donnees['id'].'">
                Delete</label></td>';
          echo '</tr>';
        }

        $resultat->closeCursor();
      ?>

      </tbody>
    </table>
   </form>  
  </div>
  
  <div class="center_form">
    <p>Ajouter une ville et ses températures</p>
    <!-- Form self submit for insertion handling-->
    <form action="index.php" method="post">
      <label for="ville">Ville:</label><br>
      <input type="text" id="ville" name="ville" value="" ><br>

      <label for="bas">bas:</label><br>
      <input type="text" id="bas" name="bas" value="" ><br>
      
      <label for="haut">Haut:</label><br>
      <input type="text" id="haut" name="haut" value="" ><br><br>
      
      <input type="submit" value="submit">
    </form> 
  </div>
</body>
</html>


