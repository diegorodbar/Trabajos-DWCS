<?php 
    // Habilitar sesiones
    session_start();
    // ob_end_flush();
    // Variables
    $idiomas = ["Español", "Inglés", "Ruso", "Galego"];
    $perfil = ['Si', 'No'];
    $zonas = ['GMT-2', 'GMT-1', 'GMT', 'GMT+1', 'GMT+2'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CDN -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <title>Preferencias</title>
    </head>
    <body style="background:silver;">
    <?php
        // Sesiones
        if (isset($_POST['enviar'])) {
            $_SESSION['idioma'] = $_POST['idioma'];
            $_SESSION['perfil'] = $_POST['perfil'];
            $_SESSION['zona'] = $_POST['zona'];
            $_SESSION['mensaje'] = "Preferencias de usuario guardadas";
        }
    ?>
        <div class="container mt-5">
            <div class="d-flex justify-content-center h-100">
                <div class="card">
                    <div class="card" style="width: 30rem">
                        <div class="card-header">
                            <h2>Preferencias de usuario</h2>
                        </div>
                    <div class="card-body">
                    <?php
                        // Comprobar sesión para mostrar mensaje
                        if (isset($_SESSION['mensaje'])) {
                            echo "<p class='card-text textprimary'>{$_SESSION['mensaje']}</p>";
                            unset($_SESSION['mensaje']);
                        }
                    ?>
                    <form name="preferencias" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <!-- Idioma -->
                        <label class="" for="id">Idioma</label>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-language"></i></span>
                            </div>
                            <select class="form-control" name="idioma" id="id">
                            <?php
                                foreach ($idiomas as $k => $v) {
                                    if (isset($_SESSION['idioma']) && $_SESSION['idioma'] == $k) {
                                        echo "<option value='$k' selected>$v</option>";
                                    } else {
                                        echo "<option value='$k'>$v</option>";
                                    }
                                    echo ">$idioma</option>";
                                }
                            ?>
                            </select>
                        </div>
                        <!-- Perfil público -->
                        <label class="" for="perfil">Perfil público</label>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fausers"></i></span>
                            </div>
                            <select class="form-control" name="perfil" id="pe">
                            <?php
                                foreach ($perfil as $k => $v) {
                                    if (isset($_SESSION['perfil']) && $_SESSION['perfil'] == $k) {
                                        echo "<option value='$k' selected>$v</option>";
                                    } else {
                                        echo "<option value='$k'>$v</option>";
                                    }
                                }
                            ?>
                            </select>
                        </div>
                        <!-- Zona horaria -->
                        <label class="" for="zona">Zona horaria</label>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                            <select class="form-control" name="zona" id="zh">
                            <?php
                                foreach ($zonas as $k => $v) {
                                    if (isset($_SESSION['zona']) && $_SESSION['zona'] == $k) {
                                        echo "<option value='$k' selected>$v</option>";
                                    } else {
                                        echo "<option value='$k'>$v</option>";
                                    }
                                }
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <a href="mostrar.php" class="btn btn-primary">Mostrar preferencias</a>
                            <input type="submit" name="enviar" value="Guardar preferencias" class="btn float-right login_btn">
                        </div>
                    </form>
                    <?php
                        // Errores
                        if (isset($_SESSION['error'])) {
                            echo "<div class='mt-3 text-danger font-weight-bold text-lg'>";
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>