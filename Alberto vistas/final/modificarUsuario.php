<?php
	error_reporting(0);
	session_start();

	$id = $_SESSION['id'];
	$host = "localhost";
	$port = "3306";
	$conexion = mysqli_connect($host.":".$port,"root",NULL);
	if(! $conexion){
		exit('Error de conexion');
	}

	mysqli_select_db($conexion,"gimnasio");
	$consulta = "SELECT nombre, apellidos, telefono, email, tipo FROM usuario WHERE id = $id";
	$resultado = mysqli_query($conexion,$consulta);
	
	foreach ($resultado as $data) {			
		$i = 0;
		foreach($data as $persona){
			$array[$i] = $persona;
			$i++;
		}			
	}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="añadirUsuario.css" media="screen">
    <title>Añadir usuarios</title>

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
  	<h2> Añade un nuevo usuario </h2>

  	<form method="post" action="">
  	<div>
  		<label for="name">Nombre:</label>
		<input type="text" id="name" name="name" requiredminlength="4" value="<?php echo $array[0] ?>" maxlength="20" size="10">
		<label for="surname">Apellido:</label>
		<input type="text" id="surname" name="surname" requiredminlength="4" value="<?php echo $array[1] ?>" maxlength="20" size="10">
  	</div>
  	<p></p>
  	<div>
  		<label for="telefono">Teléfono:</label>
		<input type="text" id="telf" name="telf" requiredminlength="9" value="<?php echo $array[2] ?>" maxlength="9" size="10">
  	</div>
  	<p></p>
  	<div>
  		<label for="name">Email:</label>
		<input type="text" id="email" name="email" requiredminlength="10" maxlength="100" value="<?php echo $array[3] ?>" size="10">
		<label for="tipo">Tipo de usuario:</label>
		<input type="text" id="tipo" name="tipo" value="<?php echo $array[4] ?>" min="1" max="2">
  	</div>
  	<p></p>
  	<button type="submit" name="volver">Volver atrás</button>
  	<button type="submit" name="guardar">Guardar</button>
  </form>


<?php


		if(isset($_POST["guardar"]))
		{

			$nombre = $_POST['name'];
			$apellido = $_POST['surname'];
			$email = $_POST['email'];
			$tipo = $_POST['tipo'];
			$telefono = $_POST['telf'];
			try{
			$consulta = "UPDATE usuario SET nombre = '$nombre', apellidos = '$apellido', telefono = '$telefono', email = '$email', tipo = '$tipo' WHERE id = '$id'";
			$resultado = mysqli_query($conexion,$consulta);
			
			echo "La base de datos ha sido actualizada!";
			}
			catch(Exception $e){
				echo "Error: " .  $e->getMessage();
			}
			
		}


	if(isset($_POST["volver"]))
	{
			header("Location: administrarUsuarios.php");
	}
?>
<footer>
        <div class="enlaces">
        	<a>Muscle Temple</a>
            <a>Legals</a>
            <a>Contact Us</a>
        </div>

        <div class="redes_sociales">
            <img src="img/iconTwitter.png" alt="Twitter">
            <img src="img/iconInstagram.png" alt="Instagram">
            <img src="img/iconFacebook.png" alt="Facebook">
        </div>
</footer>
 </body>
 </html>