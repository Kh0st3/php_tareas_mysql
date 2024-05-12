<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: /primeraactividad');
}

require 'database.php';

$message = '';

/* Validación de escritura en los campos para continuar */
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    // Validación para asegurar que no haya espacios en el email y la contraseña
    if (strpos($_POST['email'], ' ') !== false || strpos($_POST['password'], ' ') !== false) {
        $message = 'Los campos no pueden contener espacios';
    } else {
        $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
        $records->bindParam(':email', $_POST['email']);
        $records->execute();
        $results= $records->fetch(PDO::FETCH_ASSOC);

        /* Método de verificación del password con la base de datos MySQL */
        if (is_array($results) && count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
            $_SESSION['user_id'] = $results['id'];
            header('Location: /primeraactividad');
        } else {
            $message = 'Por favor valida que sean correctos los datos suministrados email y password, ya que no coinciden';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso</title>

    <!-- Se realiza la descarga de la fuente desde el sitio web de google fonts para la implementacion del desarrollo-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Ubuntu+Sans:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    
    <!-- Se realiza llamado de los estilos para que sea aplicado a la pagina-->
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

    <?php require 'partials/header.php' ?>
    
    <h1>Ingresa</h1>

    <span> o <a href="signup.php">Registrate</a></span>

    <?php if (!empty($message)) : ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form class="" action="login.php" method="post">
        <input type="text" name="email" placeholder="Ingresa tu correo electronico">
        <input type="password" name="password" placeholder="Ingresa tu contraseña">
        <input type="submit" value="Send">
    </form>

</body>
</html>
