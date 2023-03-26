<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}else{
    if($_SESSION["tipousuario_id"] !== 2) {
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
    <title>Pagina principal de la web</title>

    <!--INCLUIR FUENTE-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    <!--FIN FUENTE-->
</head>


<header>
        <!-- Image logo -->
        <img src="img/logo.svg" alt="logo Empresa">
        <nav class="navbar">
            <a href="instalaciones.html">Instalaciones</a>
            <a href="horarios.html">Horarios</a>
            <a href="actividades.html">Actividades</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
</header>
<body>
    <form method="post">
    <?php

      /*  session_start();
        $host = "localhost";
        $port = "3306";
        $link = mysqli_connect($host.":".$port,"root",NULL);
        if(! $link){
            exit('Error de conexion');
        }*/
        mysqli_select_db($link,"gimnasio");

        $idreserva = $_SESSION['identreno'];

        $consulta = "SELECT * FROM entrenamiento WHERE reservaentrenamiento_id ='$idreserva'";
        $resultado = mysqli_query($link,$consulta);

        $num = mysqli_num_rows($resultado);

        echo "<table class=\"styled-table\">";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>Repeticiones</th>";
                    echo "<th>Peso</th>";
                    echo "<th>Duracion</th>";
                    echo "<th>Actividad</th>";
                    echo "<th>Intensidad</th>";
                    echo "<th>Tipo de entrenamiento</th>";
                    echo "<th> </th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
                for($i = 0; $i < $num; $i++){
                    echo "<tr>";
                    $fila = mysqli_fetch_array($resultado);
                    echo "<td>".$fila["repeticiones"]."</td>";
                    echo "<td>".$fila["peso"]."</td>";
                    echo "<td>".$fila["duracion"]."</td>";

                    $actividad = $fila["actividad_id"];
                    $consulta = "SELECT nombre FROM actividad WHERE id = '$actividad'";
                    $nombre = mysqli_query($link,$consulta);
                    $actividad = mysqli_fetch_array($nombre);
                    echo "<td>".$actividad["nombre"]."</td>";

                    $intensidad = $fila["intensidad_id"];
                    $consulta = "SELECT nombre FROM intensidad WHERE id = '$intensidad'";
                    $nombre = mysqli_query($link,$consulta);
                    $intensidad = mysqli_fetch_array($nombre);
                    echo "<td>".$intensidad["nombre"]."</td>";
                    
                    $tipo = $fila["tipoentrenamiento_id"];
                    $consulta = "SELECT nombre FROM tipoentrenamiento WHERE id = '$tipo'";
                    $nombre = mysqli_query($link,$consulta);
                    $tipo = mysqli_fetch_array($nombre);
                    echo "<td>".$tipo["nombre"]."</td>";

                    $idfila = $fila["id"];
                    echo "<td><button type=\"submit\" class=\"button-34\" name=\"delete\" value='$idfila'>Borrar</button></td>";
                    echo "</tr>";

                }
    ?>
        </tbody>
    </table>
    <button type="submit" class="button-34" name="back" >Volver</button>
</form>
    <?php
        if(isset($_POST['delete'])){
            $identreno = $_POST['delete'];
            $consulta = "DELETE FROM entrenamiento WHERE id ='$identreno'";
            $resultado = mysqli_query($link,$consulta);
            header("Location: mostrarEntrenamientos.php");
        }

        if(isset($_POST['back'])){
            header("Location: administrarReservas.php");
        }
    ?>

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