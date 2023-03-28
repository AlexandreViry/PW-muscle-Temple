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

$email = $dni = $name = $surname = $phone = "";
$new_email = $new_dni = $new_name = $new_surname = $new_phone = "";
$new_email_err = $new_dni_err = $new_name_err = $new_surname_err = $new_phone_err = "";

//Get user data
// Prepare a select statement
$sql = "SELECT nombre, apellidos, email, dni, telefono FROM usuario WHERE id = ?";
        
if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $_SESSION["idclient"];

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $name, $surname, $email, $dni, $phone);
    mysqli_stmt_fetch($stmt);
}

// Close statement
mysqli_stmt_close($stmt);

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate new name
    if(empty(trim($_POST["nombre"]))){
        $new_name_err = "Por favor ingresa un nombre.";     
    } elseif(strlen(trim($_POST["nombre"])) < 2){
        $new_name_err = "El nombre debe tener al menos 3 caracteres.";
    } else{
        $new_name = trim($_POST["nombre"]);
    }

    // Validate new surname
    if(empty(trim($_POST["apellidos"]))){
        $new_surname_err = "Por favor ingresa tu/s apellido/s.";     
    } elseif(strlen(trim($_POST["apellidos"])) < 1){
        $new_surname_err = "El apellido debe tener al menos 2 caracteres.";
    } else{
        $new_surname = trim($_POST["apellidos"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $new_email_err = "Por favor ingrese su email de usuario.";
    }elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $new_email_err = "Por favor ingrese un email con formato válido.";
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
                    mysqli_stmt_bind_result($stmt, $id);
                    mysqli_stmt_fetch($stmt);
                    if ($id != $_SESSION["idclient"]) {
                        $new_email_err = "Este email pertenece a otro usuario.";
                    } else {
                        $new_email = trim($_POST["email"]);
                    }
                } else{
                    $new_email = trim($_POST["email"]);
                }
            } else{
                echo "Algo salió mal.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
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
        $new_dni_err = "Por favor ingrese un dni.";
    } elseif(false == comprobarDNI(trim($_POST["dni"]))){
        $new_dni_err = "El dni no es válido.";
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
                //echo $stmt;
                //echo $_SESSION["idclient"];
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id);
                    mysqli_stmt_fetch($stmt);
                    if ($id != $_SESSION["idclient"]) {
                        $new_dni_err = "Este dni pertenece a otro usuario.";
                    } else {
                        $new_dni = trim($_POST["dni"]);
                    }
                } else{
                    $new_dni = trim($_POST["dni"]);
                }
            } else{
                echo "Algo salió mal.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate new phone
    if(empty(trim($_POST["telefono"]))){
        $new_phone_err = "Por favor ingresa tu numero de telefono.";     
    } elseif(strlen(trim($_POST["telefono"])) != 9){
        $new_phone_err = "El número de teléfono debe tener 9 digitos.";
    } else{
        $new_phone = trim($_POST["telefono"]);
    }
        
    // Check input errors before updating the database
    if(empty($new_name_err) && empty($new_surname_err) && empty($new_email_err) && empty($new_dni_err) && empty($new_phone_err)){
        // Prepare an update statement
        $sql = "UPDATE usuario SET nombre = ?, apellidos = ?, email = ?, dni = ?, telefono = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssii", $param_name, $param_surname, $param_email, $param_dni, $param_phone, $param_id);
            
            // Set parameters
            $param_name = $new_name;
            $param_surname = $new_surname;
            $param_email = $new_email;
            $param_dni = $new_dni;
            $param_phone = $new_phone;
            $param_id = $_SESSION["idclient"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // data updated successfully. redirect to welcome page
                header("location: homeadmin.php");
                exit();
            } else{
                echo "Algo salió mal, por favor vuelva a intentarlo.";
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
    <title>Actualiza tu perfil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="actividades.css" media="screen">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; margin: 0 auto;}
    </style>
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
            <a href="homeadmin.php">Mi menu</a>
        </nav>
        <div style="margin-right: 20px;">
            <a href="profile.php" class="btn btn-warning">Actualiza tu perfil</a>
            <a href="logout.php" class="btn btn-danger">Cierra la sesión</a>
        </div>
    </header>
    <div class="wrapper">
        <h2>Actualizar usuario</h2>
        <p>Complete este formulario para actualizar al usuario.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_name_err)) ? 'has-error' : ''; ?>">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $new_name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_surname_err)) ? 'has-error' : ''; ?>">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control" value="<?php echo $surname; ?>">
                <span class="help-block"><?php echo $new_surname_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $new_email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_dni_err)) ? 'has-error' : ''; ?>">
                <label>Dni</label>
                <input type="text" name="dni" class="form-control" value="<?php echo $dni; ?>">
                <span class="help-block"><?php echo $new_dni_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_phone_err)) ? 'has-error' : ''; ?>">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo $phone; ?>">
                <span class="help-block"><?php echo $new_phone_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Enviar">
                <a class="btn btn-link" href="adminUsers.php">Cancelar</a>
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