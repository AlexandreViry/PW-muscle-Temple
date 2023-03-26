<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buttons</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; background-image: url("img/artemisa2.png");}
        .wrapper{ width: 350px; padding: 20px; margin: 0 auto;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Buttons</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="default">
                <input type="submit" class="btn btn-primary" value="primary">
                <input type="submit" class="btn btn-success" value="success">
                <input type="submit" class="btn btn-info" value="info">
                <input type="submit" class="btn btn-warning" value="warning">
                <input type="submit" class="btn btn-danger" value="danger">
                <input type="submit" class="btn btn-link" value="link">
                <input type="submit" class="btn btn-lg" value="lg">
                <input type="submit" class="btn btn-sm" value="sm">
                <input type="submit" class="btn btn-xs" value="xs">
                <input type="submit" class="btn btn-block" value="block">
                <input type="submit" class="btn btn-group" value="group">
                <input type="submit" class="btn btn-toolbar" value="toolbar">
                <input type="submit" class="btn btn-group-vertical" value="vertical">
                <input type="submit" class="btn btn-group-justified" value="justified">
            </div>
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate ahora</a>.</p>
        </form>
    </div>    
</body>
</html>