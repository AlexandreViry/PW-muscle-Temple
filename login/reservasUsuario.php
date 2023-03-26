<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}else{
    if($_SESSION["tipousuario_id"] !== 3) {
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


<!DOCTYPE html>
<html lang="en">

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
            <a href="homeuser.php">Menu</a>
        </nav>
        <div>
            <a href="profile.php">Perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>

    </header>

    <h1>Tus reservas</h1>


    <form method="post">
        <label for="start">Seleccione fecha:</label>
        <input type="date" id="start" name="start"
                min=<?php echo date("Y-m-d");?> max="2100-12-31"
                value="2023-03-23">

        <button type="submit" class="button-34" name="button">Ver mis reservas</button>
        <table class="styled-table">
            
            <thead>
                <tr>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de finalización</th>
                    <th>Nombre del entrenador</th>
                    <th>Apellidos del entrenador</th>
                    <th>Tipo de ejercicio</th>
                    <th>Eliminar reserva</th>
                </tr>
            </thead>
            <tbody>
            <?php

                mysqli_select_db($link,"gimnasio");                

                if(isset($_POST["button"])){

                    $fecha1 = $_POST['start'];
                    $fecha1++;
                    $fecha2 = date("Y-m-d H:i:s", strtotime($fecha1));
                    $fecha1 = date("Y-m-d H:i:s", strtotime($_POST['start']));
                    $idsession = $_SESSION['id'];

                    $consulta = "SELECT id, fechainicio, fechafin, entrenador_id, tipoentrenamiento_id FROM reservaentrenamiento WHERE usuario_id = '$idsession' AND fechainicio BETWEEN '$fecha1' AND '$fecha2'";
                    $resultado = mysqli_query($link,$consulta);
                    
                    $num = mysqli_num_rows($resultado);

                    for($i = 0; $i < $num; $i++){
                        $entreno = mysqli_fetch_array($resultado);
                        if($i%2 == 0){
                            echo "<tr>";
                                echo "<td>".$entreno["fechainicio"]."</td>";
                                echo "<td>".$entreno["fechafin"]."</td>";

                                $identrenador = $entreno["entrenador_id"];
                                $consulta = "SELECT nombre, apellidos FROM usuario WHERE id = '$identrenador'";
                                $entrenador = mysqli_query($link,$consulta);
                                $identrenador = mysqli_fetch_array($entrenador);
                                echo "<td>".$identrenador["nombre"]."</td>";
                                echo "<td>".$identrenador["apellidos"]."</td>";

                                $tipoentreno = $entreno["tipoentrenamiento_id"];
                                $consulta = "SELECT nombre FROM tipoentrenamiento WHERE id = '$tipoentreno'";
                                $tipo = mysqli_query($link,$consulta);
                                $tipoentreno = mysqli_fetch_array($tipo);
                                echo "<td>".$tipoentreno["nombre"]."</td>";

                                $id = $entreno["id"];
                                $etiqueta = "checkbox" . $i;
                                echo "<td>" . "<input type=\"checkbox\" name=\"$etiqueta\" value=\"$id\"> "."</td>";
                            echo "</tr>";
                        }
                        else{
                            echo "<tr class=\"active-row\">";
                                echo "<td>".$entreno["fechainicio"]."</td>";
                                echo "<td>".$entreno["fechafin"]."</td>";

                                $identrenador = $entreno["entrenador_id"];
                                $consulta = "SELECT nombre, apellidos FROM usuario WHERE id = '$identrenador'";
                                $entrenador = mysqli_query($link,$consulta);
                                $identrenador = mysqli_fetch_array($entrenador);
                                echo "<td>".$identrenador["nombre"]."</td>";
                                echo "<td>".$identrenador["apellidos"]."</td>";

                                $tipoentreno = $entreno["tipoentrenamiento_id"];
                                $consulta = "SELECT nombre FROM tipoentrenamiento WHERE id = '$tipoentreno'";
                                $tipo = mysqli_query($link,$consulta);
                                $tipoentreno = mysqli_fetch_array($tipo);
                                echo "<td>".$tipoentreno["nombre"]."</td>";

                                $id = $entreno["id"];
                                $etiqueta = "checkbox" . $i;
                                echo "<td>" . "<input type=\"checkbox\" name='$etiqueta' value=\"$id\"> "."</td>";
                            echo "</tr>";
                        }
                    }
                }
                if(isset($_POST["pro"])){

                    $fecha1 = $_POST['start'];
                    $fecha1++;
                    $fecha2 = date("Y-m-d H:i:s", strtotime($fecha1));
                    $fecha1 = date("Y-m-d H:i:s", strtotime($_POST['start']));
                    $idsession = $_SESSION['id'];

                    $consulta = "SELECT id, fechainicio, fechafin, entrenador_id, tipoentrenamiento_id FROM reservaentrenamiento WHERE usuario_id = '$idsession' AND fechainicio BETWEEN '$fecha1' AND '$fecha2'";
                    $resultado = mysqli_query($link,$consulta);
                    
                    $num = mysqli_num_rows($resultado);
                    error_reporting(0);
                    for($i = 0; $i < $num; $i++){
                        $etiqueta = "checkbox" . $i;
                        $valor =$_POST[$etiqueta];
                        $usu = intval($valor);
                        if($valor != ""){
                            $sql = "DELETE FROM reservaentrenamiento WHERE id = $usu";
                            mysqli_query($link,$sql);
                        }
                    }
                }
                mysqli_close($link);
            ?>
            </tbody>
        
        </table>
        <button type="submit" class="button-34" name="pro">Borrar reservas seleccionadas</button>
    </form>
    
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