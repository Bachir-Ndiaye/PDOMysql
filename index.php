<?php
require_once 'connect.php';

$errors = [];

// Instanciate a new PDO Object for SELECT
$pdoSELECT = new \PDO(DSN, USER, PASS);

// Select only firstname and lastname from friend table
$querySELECT = "SELECT lastname, firstname FROM friend";
$statement = $pdoSELECT->query($querySELECT);

/*
* If $statement = true then fetchAll
* If $statement = false return an error message
*/
if($statement === false){
    $errors = "An error occured : Statement returned false boolean";
    die($errors);
}else{
    $friends = $statement->fetchAll();
}

/*
* 1 - Validate the data from the user (correct HTTP method, correct inputs...)
* 2 - Persist the data from the user in the database
*/
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    if(isset($_POST['submit'])) {
        $submit = $_POST['submit'];
        header('Location:index.php');
    }
}

// Instanciate a new PDO Object for INSERT
$pdoINSERT = new \PDO(DSN, USER, PASS);
$queryINSERT = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";

$statementINSERT = $pdoINSERT->prepare(($queryINSERT));

$statementINSERT->bindValue(':lastname', $lastname);
$statementINSERT->bindValue(':firstname', $firstname);
$statementINSERT->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/style.css">
    <title>PDO - A friend for life</title>
</head>
<body>
    <!--List friends-->
    <div class="message">
        <h4>Here is the list of friends in the database</h4> 
    </div>
    <?php foreach($friends as $friend) { ?>
        <div class="friend-list">
            <ul>
                <li><?php echo 'Friend : '.$friend['firstname'] . ' ' . $friend['lastname'];?></li>
            </ul>
        </div>
    <?php } ?>

    <!--Indication-->    
    <div class="indication">
        <p>Fill in the form and submit. You will see your name in the list</p>
    </div>

    <!--Basic form + persist data in DB-->
    <form action="index.php" method="POST">
            <p> 
                <label for="firstname">Nom :</label>
                <input type="text" id="firstname" name="firstname" placeholder="Entrer votre nom de famille" required>
            </p>
            
            <p> 
                <label for="lastname">Prénom :</label>
                <input type="text" id="lastname" name="lastname" placeholder="Entrer votre prénom" required>
            </p>
                
            <button type="submit" name="submit">Envoyer</button>
        
        </form>
</body>
</html>