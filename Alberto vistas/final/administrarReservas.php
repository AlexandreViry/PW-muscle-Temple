
<?php

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
        </nav>
        <div>
            <a href="logout.php">Cerrar sesión</a>
        </div>

    </header>

	<h1>Bienvenido Entrenador </h1>
  	<h2> Gestión de reservas </h2>

  	    <section>
        <form method="post">
            <label for="start">Seleccione fecha:</label>
            <input type="date" id="start" name="start"
                value="2023-03-23"
                min="2018-01-01" max="2100-12-31">

            <button type="submit" class="button-34" name="button">Ver tus clientes</button>
        </form>
    </section>
    <?php
        
    ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de finalización</th>
                    <th>Nombre del usuario</th>
                    <th>Apellidos del usuario</th>
                    <th>Tipo de ejercicio</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $host = "localhost";
                $port = "3306";
                $conexion = mysqli_connect($host.":".$port,"root",NULL);
                if(! $conexion){
                    exit('Error de conexion');
                }

                mysqli_select_db($conexion,"gimnasio");

                if(isset($_POST["button"])){

                    //$idsession = $_SESSION['id'];

                    $fecha1 = $_POST['start'];
                    $fecha1++;
                    $fecha2 = date("Y-m-d H:i:s", strtotime($fecha1));
                    $fecha1 = date("Y-m-d H:i:s", strtotime($_POST['start']));

                
                    $consulta = "SELECT fechainicio, fechafin, usuario_id, tipoentrenamiento_id FROM reservaentrenamiento WHERE entrenador_id = 8 AND fechainicio BETWEEN '$fecha1' AND '$fecha2'";
					$resultado = mysqli_query($conexion,$consulta);
                    
                    $num = mysqli_num_rows($resultado);

                    for($i = 0; $i < $num; $i++){
                        $entreno = mysqli_fetch_array($resultado);
                        if($i%2 == 0){
                            echo "<tr>";
                                echo "<td>".$entreno["fechainicio"]."</td>";
                                echo "<td>".$entreno["fechafin"]."</td>";

                                $idusuario = $entreno["usuario_id"];
                                $consulta = "SELECT nombre, apellidos FROM usuario WHERE id = '$idusuario'";
                                $entrenador = mysqli_query($conexion,$consulta);
                                $idusuario = mysqli_fetch_array($entrenador);
                                echo "<td>".$idusuario["nombre"]."</td>";
                                echo "<td>".$idusuario["apellidos"]."</td>";

                                $tipoentreno = $entreno["tipoentrenamiento_id"];
                                $consulta = "SELECT nombre FROM tipoentrenamiento WHERE id = '$tipoentreno'";
                                $tipo = mysqli_query($conexion,$consulta);
                                $tipoentreno = mysqli_fetch_array($tipo);
                                echo "<td>".$tipoentreno["nombre"]."</td>";
                            echo "</tr>";
                        }
                        else{
                            echo "<tr>";
                                echo "<td>".$entreno["fechainicio"]."</td>";
                                echo "<td>".$entreno["fechafin"]."</td>";

                                $idusuario = $entreno["usuario_id"];
                                $consulta = "SELECT nombre, apellidos FROM usuario WHERE id = '$idusuario'";
                                $entrenador = mysqli_query($conexion,$consulta);
                                $idusuario = mysqli_fetch_array($entrenador);
                                echo "<td>".$idusuario["nombre"]."</td>";
                                echo "<td>".$idusuario["apellidos"]."</td>";

                                $tipoentreno = $entreno["tipoentrenamiento_id"];
                                $consulta = "SELECT nombre FROM tipoentrenamiento WHERE id = '$tipoentreno'";
                                $tipo = mysqli_query($conexion,$consulta);
                                $tipoentreno = mysqli_fetch_array($tipo);
                                echo "<td>".$tipoentreno["nombre"]."</td>";
                            echo "</tr>";
                        }
                    }
                }
            ?>
            </tbody>
        </table>
    <section>
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