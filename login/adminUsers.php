<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
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
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="reservasUsuario.css" media="screen">
    <title>Reservas</title>

    <!--INCLUIR FUENTE-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    <!--FIN FUENTE-->
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
            <a class="btn btn-link" href="hometrainer.php">Menu</a>

        </nav>
        <div>
            <a href="logout.php">Cerrar sesión</a>
        </div>

    </header>

	<h1> Gestión de usuarios </h1>
    <h2> Usuarios </h2>

    <section>
    <form method="post">

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Dni</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Tipo de usuario</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
            
                mysqli_select_db($link,"gimnasio");

                    $idsession = $_SESSION['id'];

                    $consulta = "SELECT * FROM usuario";
					$resultado = mysqli_query($link,$consulta);
                    
                    $num = mysqli_num_rows($resultado);
                    for($i = 0; $i < $num; $i++){
                        $user = mysqli_fetch_array($resultado);
                        if($i%2 == 0){
                            echo "<tr>";

                                $idusuario = $user["id"];
                                $consulta = "SELECT * FROM usuario WHERE id = '$idusuario'";
                                $entrenador = mysqli_query($link,$consulta);
                                $idusuario = mysqli_fetch_array($entrenador);
                                echo "<td>".$idusuario["id"]."</td>";
                                echo "<td>".$idusuario["nombre"]."</td>";
                                echo "<td>".$idusuario["apellidos"]."</td>";
                                echo "<td>".$idusuario["dni"]."</td>";
                                echo "<td>".$idusuario["telefono"]."</td>";
                                echo "<td>".$idusuario["email"]."</td>";

                                $typeuser = $user["tipousuario_id"];
                                $consulta = "SELECT nombre FROM tipousuario WHERE id = '$typeuser'";
                                $type = mysqli_query($link,$consulta);
                                $typeuser = mysqli_fetch_array($type);
                                echo "<td>".$typeuser["nombre"]."</td>";

                                $id = $user["id"];
                                $etiqueta = "checkbox";
                                echo "<td><button type=\"submit\" class=\"button-34\" name=\"Modificar\" value='$id'>Modificar</button></td>";
                            echo "</tr>";
                        }
                        else{
                            echo "<tr>";
                                
                                $idusuario = $user["id"];
                                $consulta = "SELECT * FROM usuario WHERE id = '$idusuario'";
                                $entrenador = mysqli_query($link,$consulta);
                                $idusuario = mysqli_fetch_array($entrenador);
                                echo "<td>".$idusuario["id"]."</td>";
                                echo "<td>".$idusuario["nombre"]."</td>";
                                echo "<td>".$idusuario["apellidos"]."</td>";
                                echo "<td>".$idusuario["dni"]."</td>";
                                echo "<td>".$idusuario["telefono"]."</td>";
                                echo "<td>".$idusuario["email"]."</td>";

                                $typeuser = $user["tipousuario_id"];
                                $consulta = "SELECT nombre FROM tipousuario WHERE id = '$typeuser'";
                                $type = mysqli_query($link,$consulta);
                                $typeuser = mysqli_fetch_array($type);
                                echo "<td>".$typeuser["nombre"]."</td>";

                                $id = $user["id"];
                                $etiqueta = "checkbox";
                                echo "<td><button type=\"submit\" class=\"button-34\" name=\"Modificar\" value='$id'>Modificar</button></td>";
                            echo "</tr>";
                        }
                    }
            ?>
            </tbody>
        </table>

        <?php

            if(isset($_POST['Modificar'])){
                session_start();
                $valor = $_POST['Modificar'];
                $_SESSION["idclient"] = htmlentities($valor);
                header("Location: adminModifyUser.php");
            }

    ?>
            </table>
        </form>
    </section>

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

</body>

</html>