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
        <img src="img/logo.svg" alt="logo Empresa" style="width: 100px; height: 100px; max-width: 100%; max-height: 100%;">
        <nav class="navbar" style="display: flex; align-items: center; margin-top: 20px;">
            <a href="index.html">Menu Principal</a>
            <a href="#">Instalaciones</a>
            <a href="#">Horarios</a>
            <a href="actividades.html">Actividades</a>
            <a href="blog.html">Blog</a>
            <a href="hometrainer.php">Mi menu</a>
        </nav>
        <div style="margin-right: 20px;">
            <a href="profile.php" class="btn btn-warning"style="display: inline-block;">Actualiza tu perfil</a>
            <a href="logout.php" class="btn btn-danger" style="display: inline-block;">Cierra la sesión</a>
        </div>
    </header>

	<h1> Clases programadas </h1>
  	<h2> Gestión de reservas </h2>

  	<section>
    <form method="post">
        <label for="start">Seleccione fecha:</label>
        <input type="date" id="start" name="start"
            value=<?php if(isset($_POST['start'])) echo $_POST['start']; ?>
            min="2023-01-01" max="2100-12-31">
        <button type="submit" class="button-34" name="button">Ver tus clientes</button>
        <button type="submit" class="button-34" name="add">Añade entrenamiento</button>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora de Inicio</th>
                    <th>Hora de finalización</th>
                    <th>Nombre del usuario</th>
                    <th>Apellidos del usuario</th>
                    <th>Tipo de entrenamiento</th>
                    <th>Selecciona</th>
                    <th> <th>
                </tr>
            </thead>
            <tbody>
            <?php
            
                mysqli_select_db($link,"gimnasio");

                if(isset($_POST["button"])){

                    $idsession = $_SESSION['id'];

                    $fecha1 = $_POST['start'];
                    $fecha1++;
                    $fecha2 = date("Y-m-d H:i:s", strtotime($fecha1));
                    $fecha1 = date("Y-m-d H:i:s", strtotime($_POST['start']));
                
                    $consulta = "SELECT id, fechainicio, fechafin, usuario_id, tipoentrenamiento_id FROM reservaentrenamiento WHERE entrenador_id = '$idsession' AND fechainicio BETWEEN '$fecha1' AND '$fecha2'";
					$resultado = mysqli_query($link,$consulta);
                    
                    $num = mysqli_num_rows($resultado);
                    for($i = 0; $i < $num; $i++){
                        $entreno = mysqli_fetch_array($resultado);
                        if($i%2 == 0){
                            echo "<tr>";
                                echo "<td>" . date('d-m-y', strtotime($entreno['fechainicio'])) . "</td>";
                                echo "<td>" . date('H:i', strtotime($entreno['fechainicio'])) . "</td>";
                                echo "<td>" . date('H:i', strtotime($entreno['fechafin'])) . "</td>";

                                $idusuario = $entreno["usuario_id"];
                                $consulta = "SELECT nombre, apellidos FROM usuario WHERE id = '$idusuario'";
                                $entrenador = mysqli_query($link,$consulta);
                                $idusuario = mysqli_fetch_array($entrenador);
                                echo "<td>".$idusuario["nombre"]."</td>";
                                echo "<td>".$idusuario["apellidos"]."</td>";

                                $tipoentreno = $entreno["tipoentrenamiento_id"];
                                $consulta = "SELECT nombre FROM tipoentrenamiento WHERE id = '$tipoentreno'";
                                $tipo = mysqli_query($link,$consulta);
                                $tipoentreno = mysqli_fetch_array($tipo);
                                echo "<td>".$tipoentreno["nombre"]."</td>";

                                $id = $entreno["id"];
                                $etiqueta = "checkbox";
                                echo "<td>" . "<input type=\"radio\" name='$etiqueta' value=\"$id\"> "."</td>";
                                echo "<td><button type=\"submit\" class=\"button-34\" name=\"ver\" value='$id'>Ver</button></td>";
                            echo "</tr>";
                        }
                        else{
                            echo "<tr>";
                                echo "<td>" . date('d-m-y', strtotime($entreno['fechainicio'])) . "</td>";
                                echo "<td>" . date('H:i', strtotime($entreno['fechainicio'])) . "</td>";
                                echo "<td>" . date('H:i', strtotime($entreno['fechafin'])) . "</td>";

                                $idusuario = $entreno["usuario_id"];
                                $consulta = "SELECT nombre, apellidos FROM usuario WHERE id = '$idusuario'";
                                $entrenador = mysqli_query($link,$consulta);
                                $idusuario = mysqli_fetch_array($entrenador);
                                echo "<td>".$idusuario["nombre"]."</td>";
                                echo "<td>".$idusuario["apellidos"]."</td>";

                                $tipoentreno = $entreno["tipoentrenamiento_id"];
                                $consulta = "SELECT nombre FROM tipoentrenamiento WHERE id = '$tipoentreno'";
                                $tipo = mysqli_query($link,$consulta);
                                $tipoentreno = mysqli_fetch_array($tipo);
                                echo "<td>".$tipoentreno["nombre"]."</td>";
                                $id = $entreno["id"];
                                $etiqueta = "checkbox";
                                echo "<td>" . "<input type=\"radio\" name='$etiqueta' value=\"$id\"> "."</td>";
                                echo "<td><button type=\"submit\" class=\"button-34\" name=\"ver\" value='$id'>Ver</button></td>";
                            echo "</tr>";
                        }
                    }
                }
            ?>
            </tbody>
        </table>

        <?php

            if(isset($_POST['ver'])){
                session_start();
                $valor = $_POST['ver'];
                $_SESSION["identreno"] = htmlentities($valor);
                header("Location: mostrarEntrenamientos.php");
            }

            if(isset($_POST["add"])){
                $fecha1 = $_POST['start'];
                $fecha1++;
                $fecha2 = date("Y-m-d H:i:s", strtotime($fecha1));
                $fecha1 = date("Y-m-d H:i:s", strtotime($_POST['start']));
                
                $consulta = "SELECT id, fechainicio, fechafin, usuario_id, tipoentrenamiento_id FROM reservaentrenamiento WHERE entrenador_id = 8 AND fechainicio BETWEEN '$fecha1' AND '$fecha2'";
                $resultado = mysqli_query($link,$consulta);
                $num = mysqli_num_rows($resultado);
                //error_reporting(0);
                
                    $etiqueta = "checkbox";
                    if(isset($_POST[$etiqueta])){
                    $valor =$_POST[$etiqueta];

                    $usu = intval($valor);  
                    if($valor != ""){
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
                            echo "<td><input type=\"number\" name=\"Repeticiones\"></td>";
                            echo "<td><input type=\"number\" name=\"Peso\"></td>";
                            echo "<td><input type=\"time\" name=\"Duracion\"></td>";
                            echo "<td><select name=\"actividad\">";
                                $consulta = "SELECT nombre FROM actividad";
                                $resultado = mysqli_query($link,$consulta);
                                
                                $num = mysqli_num_rows($resultado);
                                for($i = 0; $i < $num; $i++){
                                    $actividades = mysqli_fetch_array($resultado);
                                    $nomact = $actividades["nombre"];
                                    echo "<option>$nomact</option>";
                                }
                            echo "</select></td>";

                            echo "<td><select name=\"intensidad\">";
                                $consulta = "SELECT nombre FROM intensidad";
                                $resultado = mysqli_query($link,$consulta);
                                
                                $num = mysqli_num_rows($resultado);
                                for($i = 0; $i < $num; $i++){
                                    $actividades = mysqli_fetch_array($resultado);
                                    $nomact = $actividades["nombre"];
                                    echo "<option>$nomact</option>";
                                }
                            echo "</select></td>";

                            echo "<td><select name=\"tipo\">";
                                $consulta = "SELECT nombre FROM tipoentrenamiento";
                                $resultado = mysqli_query($link,$consulta);
                                
                                $num = mysqli_num_rows($resultado);
                                for($i = 0; $i < $num; $i++){
                                    $actividades = mysqli_fetch_array($resultado);
                                    $nomact = $actividades["nombre"];
                                    echo "<option>$nomact</option>";
                                }
                            echo "</select></td>";
                            echo "<td><button type=\"submit\" class=\"button-34\" name=\"save\" value='$usu'>Guardar</button></td>";
                            echo "</tbody>";
                    }
                }else{
                    echo "Seleccione un cliente para añadir el entrenamiento.";
                }
            }
    ?>
            </table>
        </form>
    </section>

    <?php
        if(isset($_POST['save'])){

            $identreno = $_POST['save'];

            $inten = $_POST['intensidad'];
            $tipo = $_POST['tipo'];
            $acti = $_POST['actividad'];
            $repeticiones = $_POST['Repeticiones'];
            $peso = $_POST['Peso'];
            $duracion = $_POST['Duracion'];

            $consulta = "SELECT id FROM tipoentrenamiento WHERE nombre = '$tipo'";
            $resultado = mysqli_query($link,$consulta);
            $tipo = mysqli_fetch_array($resultado);
            $idtipo = $tipo["id"];

            $consulta = "SELECT id FROM intensidad WHERE nombre = '$inten'";
            $resultado = mysqli_query($link,$consulta);
            $tipo = mysqli_fetch_array($resultado);
            $idinten = $tipo["id"];

            $consulta = "SELECT id FROM actividad WHERE nombre = '$acti'";
            $resultado = mysqli_query($link,$consulta);
            $tipo = mysqli_fetch_array($resultado);
            $idacti = $tipo["id"];

            $consulta = "INSERT INTO entrenamiento (repeticiones, peso, duracion, actividad_id, intensidad_id, tipoentrenamiento_id, reservaentrenamiento_id) VALUES ('$repeticiones', '$peso', '$duracion', '$idacti', '$idinten', '$idtipo', '$identreno')";

            mysqli_query($link,$consulta);

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
        <p>© 2023 MuscleTemple, All right reserved.</p>
    </footer>
</body>

</html>