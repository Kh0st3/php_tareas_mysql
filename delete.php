<?php
session_start();
require 'database.php';

$message = '';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Verificar si el usuario ha confirmado la eliminación de la cuenta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmacion'])) {
    if ($_POST['confirmacion'] === 'si') {
        $user_id = $_SESSION['user_id'];
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        if ($stmt->execute()) {
            session_destroy(); // Destruir la sesión
            header("Location: index.php"); // Redirigir a la página de inicio después de eliminar la cuenta
            exit();
        } else {
            $message = 'Disculpas!!! Ha ocurrido un error al intentar eliminar tu cuenta';
        }
    } elseif ($_POST['confirmacion'] === 'no') {
        // Si el usuario selecciona "no", redirigirlo a la página principal
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar cuenta</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php require 'partials/header.php' ?>

    <h1>Eliminar cuenta</h1>

    <?php if(!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.</p>

    <form action="delete.php" method="post">
        <input type="submit" name="confirmacion" value="si">
        <input type="submit" name="confirmacion" value="no">
    </form>

</body>
</html>
