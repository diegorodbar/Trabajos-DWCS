<?php
    session_start();
    // ob_end_flush();
    
    if(isset($_POST['enviar'])) {
        session_unset();
        $mensaje = "Preferencias borradas";
    } else {
        $mensaje = "Debes fijar las preferencias";
    }
    $idiomas = ["Español", "Inglés", "Ruso", "Galego"];
    $perfiles = ['Si', 'No'];
    $zonas = ['GMT-2', 'GMT-1', 'GMT', 'GMT+1', 'GMT+2'];
    $miidioma = isset($_SESSION['idioma']) ? $idiomas[$_SESSION['idioma']] : "No establecido";
    $miperfil = isset($_SESSION['perfil']) ? $perfiles[$_SESSION['perfil']] : "No establecido";
    $mizona = isset($_SESSION['zona']) ? $zonas[$_SESSION['zona']] : "No establecido";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- Bootstrap CDN -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <title>Preferencias</title>
    </head>
    <body style="background: gray">
        <div class="container mt-4">
            <div class="card text-white bg-success mb-3 m-auto" style="width:35rem">
                <div class="card-body">
                    <h2 class="card-title"><i class="fas fa-user-cog"></i> Preferencias de usuario</h2>
                    <?php
                        if (isset($_SESSION['mensaje'])) {
                            echo "<p class='card-text text-danger font-weight-bold' style='font-size: 1.1em'>$mensaje</p>";
                            unset($_SESSION['mensaje']);
                        }
                    ?>
                    <p class="card-text" style="font-size: 1.1em">
                        <span class="font-weight-gold">Idioma: <?php echo $miidioma; ?>
                    </p>
                    <p class="card-text" style="font-size: 1.1em">
                        <span class="font-weight-gold">Perfil público: <?php echo $miperfil; ?>
                    </p>
                    <p class="card-text" style="font-size: 1.1em">
                        <span class="font-weight-gold">Zona horaria: <?php echo $mizona; ?>
                    </p>
                    <form name="b" class="form-inline" action='<?php echo $_SERVER['PHP_SELF']; ?>' method='POST'>
                        <a href="preferencias.php" class="btn btn-primary mr-2" style="font-size: 1.1em">Modificar preferencias</a>
                        <input type="submit" class="btn btn-danger" name="enviar" value="Borrar preferencias" style="font-size: 1.1em">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>