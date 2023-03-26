<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $surname = $phone = "";
$new_name = $new_surname = $new_phone = "";
$new_name_err = $new_surname_err = $new_phone_err = "";

// Define type home 
switch($_SESSION['tipousuario_id']){
    case 1: $home = "homeadmin.php";
    break;
    case 2: $home = "hometrainer.php";
    break;
    case 3: $home = "homeuser.php";
    break;
    default: $home = "logout.php";
}

//Get user data
// Prepare a select statement
$sql = "SELECT nombre, apellidos, telefono FROM usuario WHERE id = ?";
        
if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $_SESSION["id"];

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $name, $surname, $phone);
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

    // Validate new phone
    if(empty(trim($_POST["telefono"]))){
        $new_phone_err = "Por favor ingresa tu numero de telefono.";     
    } elseif(strlen(trim($_POST["telefono"])) != 9){
        $new_phone_err = "El número de teléfono debe tener 9 digitos.";
    } else{
        $new_phone = trim($_POST["telefono"]);
    }
        
    // Check input errors before updating the database
    if(empty($new_name_err) && empty($new_surname_err) && empty($new_phone_err)){
        // Prepare an update statement
        $sql = "UPDATE usuario SET nombre = ?, apellidos = ?, telefono = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_name, $param_surname, $param_phone, $param_id);
            
            // Set parameters
            $param_name = $new_name;
            $param_surname = $new_surname;
            $param_phone = $new_phone;
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // data updated successfully. redirect to welcome page
                header("location: $home");
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
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; margin: 0 auto;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Actualiza tu perfil</h2>
        <p>Complete este formulario para actualizar su perfil.</p>
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
            <div class="form-group <?php echo (!empty($new_phone_err)) ? 'has-error' : ''; ?>">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo $phone; ?>">
                <span class="help-block"><?php echo $new_phone_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Enviar">
                <a class="btn btn-link" href="<?php echo $home; ?>">Cancelar</a>
                <a class="btn btn-link" href="reset-password.php">Cambiar contraseña</a>
            </div>
        </form>
    </div>
</body>
</html>