<?php
session_start();

require 'database.php';

if(isset($_SESSION['user_id'])){
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE id=:id');
    $records-> bindParam(':id', $_SESSION['user_id']);
    $records-> execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0){
        $user = $results;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a RAM</title>

    <!-- Se realiza la descarga de la fuente desde el sitio web de google fonts para la implementacion del desarrollo-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Ubuntu+Sans:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    
    <!-- Se realiza llamado de los estilos para que sea aplicado a la pagina-->
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

    <?php require 'partials/header.php' ?>

    <?php if(!empty($user)): ?>
        <br>Bienvenido. <?= $user['email'] ?>
        <br>Haz ingresado satisfactoriamente.
        <form action="logout.php" method="post">
            <input type="submit" value="Salir">
        </form>
        <form action="delete.php" method="post">
            <input type="submit" value="Eliminar Usuario">
        </form>
    <?php else: ?>
        <h1>Por favor ingresa o regístrate</h1>
        <a href="login.php">Ingresa</a> o
        <a href="signup.php">Regístrate</a>
    <?php endif; ?>

</body>
</html>
