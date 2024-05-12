<?php 
require 'database.php';

$message = '';

/**Comprobacion de ingreso de datos en el formulario */
if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])){
    // Verificar si las contraseñas coinciden
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $message = 'Las contraseñas no coinciden';
    } else {
        // Verificar si el correo electrónico ya está registrado
        $check_email_query = "SELECT id FROM users WHERE email = :email";
        $check_stmt = $conn->prepare($check_email_query);
        $check_stmt->bindParam(':email', $_POST['email']);
        $check_stmt->execute();

        if($check_stmt->rowCount() > 0) {
            $message = 'Este correo electrónico ya está registrado';
        } else {
            // Verificar si hay espacios en el campo de correo electrónico
            if (strpos($_POST['email'], ' ') !== false) {
                $message = 'El correo electrónico no puede contener espacios';
            } else {
                // Validar el formato del correo electrónico
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $message = 'El formato del correo electrónico es incorrecto';
                } else {
                    // Insertar el nuevo usuario si el correo electrónico no está registrado y cumple con el formato
                    $sql = "INSERT INTO users (email, password) VALUES(:email, :password)";
                    $stmt = $conn->prepare($sql);
                    /*Metodo utilizado para cifrar y guardar el password en la base de datos*/
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $stmt -> bindParam(':email', $_POST['email']);
                    $stmt -> bindParam(':password', $password);

                    /*Validacion de ejecucion del stmt correctamente*/
                    if ($stmt -> execute()) {
                        $message = 'Usuario Nuevo Creado Satisfactoriamente';
                    } else {
                        $message = 'Disculpas!!! Ha ocurrido un error creando tu cuenta';
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate</title>

    <!-- Se realiza la descarga de la fuente desde el sitio web de google fonts para la implementacion del desarrollo-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Ubuntu+Sans:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    
    <!-- Se realiza llamado de los estilos para que sea aplicado a la pagina-->
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <h1>Registrate</h1>

    <span>o <a href="login.php">Ingresa</a></span>

    <form class="" action="signup.php" method="post">
        <input type="text" name="email" placeholder="Ingresa tu correo electronico" required>
        <input type="password" name="password" placeholder="Ingresa tu contraseña" required>
        <input type="password" name="confirm_password" placeholder="Confirma tu contraseña" required>
        <input type="submit" value="Send">
    </form>

</body>
</html>
