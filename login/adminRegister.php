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

// Define variables and initialize with empty values
$email = $password = $confirm_password = $dni = $name = $surname = $phone = $typeuser = $typeuser_id = "";
$email_err = $password_err = $confirm_password_err = $dni_err = $name_err = $surname_err = $phone_err = $typeuser_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Por favor ingrese su email de usuario.";
    }elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $email_err = "Por favor ingrese un email con formato válido.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM usuario WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "Este usuario ya está en uso.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Algo salió mal.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingresa una contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña debe tener al menos 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirma tu contraseña.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Las contraseñas no coinciden.";
        }
    }

    // Function validate dni
    function comprobarDNI($dni) {
        $letra = substr($dni, -1);
        $numeros = substr($dni, 0, -1);
        $modulo = $numeros % 23;
        $letras_validas = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letra_correcta = $letras_validas[$modulo];
        if ($letra == $letra_correcta) {
            return true;
        } else {
            return false;
        }
    }

    // Validate dni
    if(empty(trim($_POST["dni"]))){
        $dni_err = "Por favor ingrese un dni.";
    } elseif(false == comprobarDNI(trim($_POST["dni"]))){
        $dni_err = "El dni no es válido.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM usuario WHERE dni = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_dni);
            
            // Set parameters
            $param_dni = trim($_POST["dni"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $dni_err = "Este dni pertenece a otro usuario.";
                } else{
                    $dni = trim($_POST["dni"]);
                }
            } else{
                echo "Algo salió mal.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate name
    if(empty(trim($_POST["nombre"]))){
        $name_err = "Por favor ingresa un nombre.";     
    } elseif(strlen(trim($_POST["nombre"])) < 2){
        $name_err = "El nombre debe tener al menos 3 caracteres.";
    } else{
        $name = trim($_POST["nombre"]);
    }

    // Validate surname
    if(empty(trim($_POST["apellidos"]))){
        $surname_err = "Por favor ingresa tu/s apellido/s.";     
    } elseif(strlen(trim($_POST["apellidos"])) < 1){
        $surname_err = "El apellido debe tener al menos 2 caracteres.";
    } else{
        $surname = trim($_POST["apellidos"]);
    }

    // Validate phone
    if(empty(trim($_POST["telefono"]))){
        $phone_err = "Por favor ingresa tu numero de telefono.";     
    } elseif(strlen(trim($_POST["telefono"])) != 9){
        $phone_err = "El número de teléfono debe tener 9 digitos.";
    } else{
        $phone = trim($_POST["telefono"]);
    }

    //Get user data
    // Prepare a select statement
    $sql = "SELECT id FROM tipousuario WHERE nombre = ?";
            
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $typeuser);

        // Set parameters
        $typeuser = trim($_POST["tipousuario"]);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $typeuser_id);
        mysqli_stmt_fetch($stmt);
    }

    // Close statement
    mysqli_stmt_close($stmt);
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($dni_err) && empty($name_err) && empty($surname_err) && empty($phone_err) && empty($typeuser_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO usuario (email, password, dni, nombre, apellidos, telefono, tipousuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssdd", $param_email, $param_password, $param_dni, $param_name, $param_surname, $param_phone, $param_typeuser_id);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_dni = $dni;
            $param_name = $name;
            $param_surname = $surname;
            $param_phone = $phone;
            $param_typeuser_id = $typeuser_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Algo salió mal, por favor inténtalo de nuevo.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="reservasUsuario.css" media="screen">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
<nav class= "navbar-collapse.in">
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



    <div class="wrapper">
        <h2>Registro</h2>
        <p>Por favor complete este formulario para crear una cuenta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>email de usuario</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirmar Contraseña</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($surname_err)) ? 'has-error' : ''; ?>">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control" value="<?php echo $surname; ?>">
                <span class="help-block"><?php echo $surname_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($dni_err)) ? 'has-error' : ''; ?>">
                <label>Dni</label>
                <input type="text" name="dni" class="form-control" value="<?php echo $dni; ?>">
                <span class="help-block"><?php echo $dni_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo $phone; ?>">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($typeuser_err)) ? 'has-error' : ''; ?>">
                <label>Tipo de usuario</label>
                <div class="container-fkuid">
                    <select name="tipousuario" required>
                        <option value="">Seleccionar...</option>
                        <?php
                            // Realizar la consulta a la base de datos
                            $result = mysqli_query($link, "SELECT nombre, id FROM tipousuario");
                            
                            // Recorrer los resultados de la consulta y generar las opciones
                            while($row = mysqli_fetch_assoc($result)) {
                            $typeuser = $row['nombre'];
                            echo "<option value=\"$typeuser\">$typeuser</option>";
                            }
                        ?>
                    </select>
                </div>
                <span class="help-block"><?php echo $typeuser_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registrar">
                <input type="reset" class="btn btn-default" value="Borrar">
            </div>
            <p><a href="homeadmin.php">Volver</a></p>
        </form>
    </div>

    <panel-footer>
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
    </panel-footer>
</nav>
</body>
</html>