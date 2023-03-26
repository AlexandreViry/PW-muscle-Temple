<?php
// Initialize the session
session_start();

// Check if the user type admin is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}else{
    if($_SESSION["tipousuario_id"] !== 1) {
        switch($_SESSION["tipousuario_id"]){
            case 1:
                header("location: homeadmin.php");
            break;
            case 2:
                header("location: hometrainer.php");
            break;
            case 3:
                header("location: homeuser.php");
            break;
            default: 
                header("location: logout.php");
        }
    }
}

// Include config file
require_once "config.php";

//Get user data name
// Prepare a select statement
$sql = "SELECT nombre FROM usuario WHERE email = ?";
        
if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $email);

    // Set parameters
    $email = $_SESSION["email"];

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $name);
    mysqli_stmt_fetch($stmt);
}

// Close statement
mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="reservasUsuario.css" media="screen">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center;}
    </style>
</head>
<body>
    <header>
        <!-- Image logo -->
        <img src="img/logo.svg" alt="logo Empresa">
        <nav class="navbar">
            <a href="instalaciones.html">Instalaciones</a>
            <a href="horarios.html">Horarios</a>
            <a href="actividades.html">Actividades</a>
            <a href="blog.html">Blog</a>
            <a class="btn btn-link" href="homeadmin.php">Menu</a>

        </nav>
        <div>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </header>

    <div class="page-header">
        <h1>Bienvenido <b><?php echo htmlspecialchars($name); ?></b> administrador del Olimpo.</h1>
    </div>
    <p>
        <a href="adminUsers.php" class="btn btn-warning">Gestionar usuarios</a>
        <a href="adminModifyUser.php" class="btn btn-warning">Modifica un usuario</a>
        <a href="adminRegister.php" class="btn btn-warning">Registra un nuevo usuario</a>
        <a href="profile.php" class="btn btn-warning">Actualiza tu perfil</a>
        <a href="logout.php" class="btn btn-danger">Cierra la sesión</a>
    </p>
</body>
<footer>
        <div class="enlaces">
            <a href="#">Muscle Temple</a>
            <a href="#">Legals</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="redes_sociales">
            <img src="img/iconTwitter.png" alt="Twitter">
            <img src="img/iconInstagram.png" alt="Instagram">
            <img src="img/iconFacebook.png" alt="Facebook">
        </div>
        <p>© 2022 MuscleTemple, All right reserved.</p>
</footer>
</html>