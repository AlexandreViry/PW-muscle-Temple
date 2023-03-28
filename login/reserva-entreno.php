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
    <link rel="stylesheet" type="text/css" href="reserva.css" media="screen">
    <link rel="stylesheet" type="text/css" href="actividades.css" media="screen">

    <title>Pagina principal de la web</title>

    <!--INCLUIR FUENTE-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    <!--FIN FUENTE-->
</head>

<script>
    function confirmacion(){

        var respuesta = confirm("¿Desea realizar esta reserva?");
        if(respuesta == true){
            return true;
        }
        else{
            return false;
        }
    }

</script>

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
            <a href="homeuser.php">Mi menu</a>
        </nav>
        <div style="margin-right: 20px;">
            <a href="profile.php" class="btn btn-warning" style="display: inline-block;">Actualiza tu perfil</a>
            <a href="logout.php" class="btn btn-danger" style="display: inline-block;">Cierra la sesión</a>
        </div>
    </header>

<?php
// Define variables and initialize with empty values
$entrenador = $tipoentrenamiento = $fecha_inicio = $fecha_fin = $hora_inicio = "";
$entrenador_err = $tipoentrenamiento_err = $fecha_inicio_err = $fecha_fin_err = $hora_inicio_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate entrenador
    if(empty(trim($_POST["entrenador"]))){
        $entrenador_err = "Por favor seleccione un entrenador.";
    } else{
        $entrenador = trim($_POST["entrenador"]);
    }

    // Validate tipoentrenamiento
    if(empty(trim($_POST["tipoentrenamiento"]))){
        $tipoentrenamiento_err = "Por favor seleccione un tipo de entrenamiento.";
    } else{
        $tipoentrenamiento = trim($_POST["tipoentrenamiento"]);
    }

    // Validate fecha inicio
    if(empty(trim($_POST["dia"])) || empty(trim($_POST["mes"])) || empty(trim($_POST["ano"]))){
        $fecha_inicio_err = "Por favor seleccione una fecha de inicio.";
    } else{
        $dia = trim($_POST["dia"]);
        $mes = trim($_POST["mes"]);
        $ano = trim($_POST["ano"]);
        $fecha_inicio = date("Y-m-d", strtotime("$ano-$mes-$dia"));
    }

    // Validate hora inicio
    if(empty(trim($_POST["hora_inicio"]))){
        $hora_inicio_err = "Por favor seleccione una hora de inicio.";
    } else{
        $hora_inicio = trim($_POST["hora_inicio"]);
    }

    // Concatenate fecha y hora de inicio
    $hora_inicio = $hora_inicio.":00:00";
   //echo $hora_inicio;

    $fecha_hora_inicio = date_create("Y-m-d H:i:s");
   // echo "$fecha_hora_inicio";

    // Concatenate fecha y hora de fin
   echo $fecha_inicio = date("Y-m-d H:i:s", strtotime($fecha_inicio.$hora_inicio));
 
    if($_POST["myRadio"]== 1){
        $fecha_hora_fin = date("Y-m-d H:i:s", strtotime($fecha_inicio));
       // echo $fecha_hora_fin;
        $fecha_hora_fin_obj = new DateTime($fecha_hora_fin);
        $fecha_hora_fin_obj->modify('+1 hours');
        $nueva_fecha_hora_fin = $fecha_hora_fin_obj->format('Y-m-d H:i:s');
        //echo $nueva_fecha_hora_fin;
    }
    if($_POST["myRadio"]== 2){
        $fecha_hora_fin = date("Y-m-d H:i:s", strtotime($fecha_inicio));
    //echo $fecha_hora_fin;
    $fecha_hora_fin_obj = new DateTime($fecha_hora_fin);
    $fecha_hora_fin_obj->modify('+2 hours');
    $nueva_fecha_hora_fin = $fecha_hora_fin_obj->format('Y-m-d H:i:s');
   // echo $nueva_fecha_hora_fin;
    }
    else{
        $fecha_hora_fin = date("Y-m-d H:i:s", strtotime($fecha_inicio));
       // echo $fecha_hora_fin;
        $fecha_hora_fin_obj = new DateTime($fecha_hora_fin);
        $fecha_hora_fin_obj->modify('+1 hours');
        $nueva_fecha_hora_fin = $fecha_hora_fin_obj->format('Y-m-d H:i:s');
    }
    
    // Check input errors before inserting in database
    if(empty($entrenador_err) && empty($tipoentrenamiento_err) && empty($fecha_inicio_err) && empty($hora_inicio_err)){

        // Prepare an insert statement
        $usuario_id = $_SESSION["id"];
        $sql = "INSERT INTO reservaentrenamiento (fechainicio, fechafin, usuario_id, tipoentrenamiento_id, entrenador_id) VALUES ('$fecha_inicio', '$nueva_fecha_hora_fin', $usuario_id, $tipoentrenamiento, $entrenador);";
    //echo $sql;

        $resultado = mysqli_query($link, $sql);
        header("location: homeuser.php");
    }

    // Close connection
    mysqli_close($link);
}
else{


$IMP_entrenador = "SELECT * FROM usuario Where tipousuario_id = '2'";
$resultado = mysqli_query($link, $IMP_entrenador);

$IMP_entreno = "SELECT * FROM tipoentrenamiento ";
$res = mysqli_query($link, $IMP_entreno);
}
?>


<div class = "Pose">
<img src="img/poseidon_reserva.png" alt="Pose" >

<form  method="post" >
    <h3> Selecciona tu entreno aspirante:</h3>
    <div class = "seleccionar">
    <label> Entrenador</label>
    <select name="entrenador">
        <?php
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
        }
        ?>
    </select>
    </div>

    <div class = "seleccionar">
    <label> Estilo</label>
    <select name="tipoentrenamiento">
        <?php
        while ($fila = mysqli_fetch_assoc($res)) {
            echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
        }
        ?>
    </select>
    </div>
    <div class = "seleccionar">
    <label> Dia</label>
    <select name="dia">
        <?php
        for ($i = 1; $i <= 31; $i++) {
            echo "<option value='" . $i . "'>" . $i . "</option>";
        }
        ?>
    </select>
    </div>

    <div class = "seleccionar">
    <label> Mes</label>
    <select name="mes">
        <?php
        for ($i = 3; $i <= 12; $i++) {
            $nombre_mes = date("F", mktime(0, 0, 0, $i, 1));
            echo "<option value='" . $i . "'>" . $nombre_mes . "</option>";
        }
        ?>
    </select>
    </div>

    <div class = "seleccionar">
    <label> Año</label>
    <select name="ano">
        <?php
        for ($i = date("Y"); $i >= 2023; $i--) {
            echo "<option value='" . $i . "'>" . $i . "</option>";
        }
        ?>
    </select>
    </div>

    <div class = "seleccionar">
    <label> Inicio</label>
    <select name="hora_inicio">
        <?php
        for ($i = 0; $i <= 23; $i++) {
            $hora = str_pad($i, 2, "0", STR_PAD_LEFT);
            echo "<option value='" . $hora . "'>" . $hora . ":00</option>";
        }

        ?>
    </select>
    </div>

    <div class = "selecionar">
        <label>  Horas de entreno:</label>
        <input type="radio" name="myRadio" value="1">1 Hora
        <input type="radio" name="myRadio" value="2">2 Horas

    </div>

    <div class = "selecionar">
    <button type="submit" onclick = "return confirmacion()">Realizar reserva</button>
    <button type="button" onclick="window.location='homeuser.php';">Cancelar</button>
    </div>
</form>
</div>
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