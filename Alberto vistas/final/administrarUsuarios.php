<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="administrarUsuarios.css" media="screen">
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
	<h1>Bienvenido Administrador</h1>
  	<h2> Gestión de usuarios </h2>

  	<form method="post" action="">
<?php
// Conexión (utilización de los valores por defecto).
$host = "localhost";
$port = "3306";
$conexion = mysqli_connect($host.":".$port,"root",NULL);
if(! $conexion){
	exit('Error de conexion');
}

mysqli_select_db($conexion,"gimnasio");

$consulta = 'SELECT id, nombre, apellidos, telefono, email, tipo FROM usuario';
$resultado = mysqli_query($conexion,$consulta);

echo "<table border = 1>";
echo "<tr style=\"background-color:#5DA7DB\">";
echo "<td>Id</td>";
echo "<td>Nombre</td>";
echo "<td>Apellido</td>";
echo "<td>telefono</td>";
echo "<td>email</td>";
echo "<td>Tipo de usuario</td>";
echo "<td>Borrar</td>";
echo "</tr>";
$i = 1;
foreach($resultado as $data){
	$cuandoId = 0;
	$id = 0;
	$etiqueta = "checkbox" . $i;
	
 	echo "<tr style=\"background-color: white\">";
	foreach($data as $muestra){	
		if($cuandoId == 0)
			$id = $muestra;

		$cuandoId++;
		echo "<td>$muestra</td>";
	}

	echo "<td><input type=\"checkbox\" name=\"$etiqueta\" value=\"$id\"></td>";
	echo "</tr>";
	$i++;
	$cuandoId = 0;
}
?>

	</form>
	<div>
		<button type="submit" name="pro">Eliminar usuario</button>
		<button type="submit" name="anadir">Añadir usuario</button>
		<button type="submit" name="pre">Modificar</button>
	</div>
<?php
	error_reporting(0);
	if (isset($_POST["pro"])) {
		for($j = 1; $j < $i; $j++) {
			$etiqueta = "checkbox" . $j;
   			$valor =$_POST[$etiqueta];
   			$usu = intval($valor);
   			if($valor != ""){
   				$sql = "DELETE FROM reservaentrenamiento WHERE usuario_id = $usu OR entrenador_id = $usu";
   				mysqli_query($conexion,$sql);
   				$sql = "DELETE FROM usuario WHERE id = $usu";
   				mysqli_query($conexion,$sql);
			}
		} 
		header("Location: administrarUsuarios.php");
	}

	if(isset($_POST["anadir"]))
	{
		header("Location: añadirUsuario.php");
	}

	if(isset($_POST["pre"]))
	{
		session_start();

		for($j = 1; $j < $i; $j++) {
			$etiqueta = "checkbox" . $j;
   			$valor =$_POST[$etiqueta];
   			$usu = intval($valor);
   			if($valor != ""){
   				$_SESSION["id"] = htmlentities($valor);
				header("Location: modificarUsuario.php");
			}
		}
	}
	
	mysqli_close($conexion);
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
        <p>© 2022 MuscleTemple, All right reserved.</p>
</footer>

</body>
</html>