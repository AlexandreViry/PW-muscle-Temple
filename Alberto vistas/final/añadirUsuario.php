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
		<input type="text" id="name" name="name" requiredminlength="4" maxlength="20" size="10">
		<label for="surname">Apellido:</label>
		<input type="text" id="surname" name="surname" requiredminlength="4" maxlength="20" size="10">
		<label for="dni">DNI:</label>
		<input type="text" id="dni" name="dni" requiredminlength="9" maxlength="9" size="10">
  	</div>
  	<p></p>
  	<div>
  		<label for="telefono">Teléfono:</label>
		<input type="text" id="telf" name="telf" requiredminlength="9" maxlength="9" size="10">
  	</div>
  	<p></p>
  	<div>
  		<label for="name">Email:</label>
		<input type="text" id="email" name="email" requiredminlength="10" maxlength="100" size="10">
		<label for="tipo">Tipo de usuario:</label>
		<input type="text" id="tipo" name="tipo">
		<label for="contraseña">Contraseña:</label>
		<input type="text" id="contraseña" name="contraseña" requiredminlength="9" maxlength="9" size="10">
  	</div>
  	<p></p>
  	<button type="submit" name="volver">Volver atrás</button>
  	<button type="submit" name="guardar">Guardar</button>
  </form>

  	<?php

  		$host = "localhost";
		$port = "3306";
		$conexion = mysqli_connect($host.":".$port,"root",NULL);
		if(! $conexion){
			exit('Error de conexion');
		}

  		if(isset($_POST["guardar"]))
		{

			$nombre = $_POST['name'];
			$apellido = $_POST['surname'];
			$email = $_POST['email'];
			$DNI = $_POST['dni'];
			$contraseña = $_POST['contraseña'];
			$tipo = $_POST['tipo'];
			$telefono = $_POST['telf'];

			$contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
			mysqli_select_db($conexion,"gimnasio");

			$consulta = 'SELECT nombre, apellidos, dni, email FROM usuario';
			$resultado = mysqli_query($conexion,$consulta);

			$noRepetido = true;
			for($i = 0; $i < mysqli_num_rows($resultado); $i++){
				$persona = mysqli_fetch_array($resultado);
				if($persona["nombre"] == $nombre && $persona["apellidos"] == $apellido){
					$i = mysqli_num_rows($resultado);
					$elemento = "Nombre y apellidos";
					$noRepetido = false;
				}
				if($persona["dni"] == $DNI){
					$i = mysqli_num_rows($resultado);
					$elemento = "Dni";
					$noRepetido = false;
				}
				if($persona["email"] == $email){
					$i = mysqli_num_rows($resultado);
					$elemento = "Email";
					$noRepetido = false;
				}
			}

			if($noRepetido){
				$consulta = "INSERT INTO usuario (nombre, apellidos, dni, telefono, email, tipo, contrasenna) VALUES ('$nombre', '$apellido', '$DNI', '$telefono', '$email', '$tipo', '$contraseña');";
				$resultado = mysqli_query($conexion,$consulta);
				mysqli_close($conexion);
				echo "Guardado con exito!";
			}
			else{
				echo "Este " . $elemento . " ya existe!";
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