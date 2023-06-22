<?php
$guardar = false;
$cargar = false;
$borrar = false;
$noExiste = false;

$file = '';
$dir = './notas/';

// Eliminar un archivo
if (isset($_GET["delete"]) && !empty($_GET['delete'])) {
    $filename = $_GET['delete'];
    if (file_exists($dir . $filename)) {
        unlink($dir . $filename);
        $borrar = true;
    }
}


// Solicitar la carga de un archivo
if (isset($_GET["file"]) && !empty($_GET['file'])) {
    $file = $_GET['file'];
    if (is_file($dir . $file)) {
        $cargar = true;
    } else {
        $noExiste = true;
    }
}

// Enviar el formulario de guardado
if (isset($_POST["save"]) && isset($_POST['content']) && isset($_POST['filename']) && !empty($_POST['filename'])) {
    $filename = $_POST['filename'];
    if (strpos($filename, ".txt") === false) {
        $filename .= ".txt";
    }
    $content = $_POST['content'];
    $file_handle = fopen($dir . $filename, 'w');
    fwrite($file_handle, $content);
    fclose($file_handle);
    $guardar = true;
}

// Se solicita el listado de los archivos en el directorio
$files = scandir($dir);
$files = array_diff($files, array('.', '..'));
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bloc de notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <?php if ($guardar) { ?>
                <div class="alert alert-success m-0" role="alert">
                    Archivo guardado exitosamente
                </div>
            <?php } else if ($cargar) { ?>
                <div class="alert alert-info m-0" role="alert">
                    Archivo fue cargado exitosamente
                </div>
            <?php } else if ($borrar) { ?>
                <div class="alert alert-danger m-0" role="alert">
                    Archivo eliminado
                </div>
            <?php } else if ($noExiste) { ?>
                <div class="alert alert-warning m-0" role="alert">
                    No existe el archivo
                </div>
            <?php } ?>

            <!-- Titulo -->
            <h2 class="m-2" style="text-align: center;">BLOC DE NOTAS</h2>

            <!-- Div para las opciones -->
            <div class="col-4  me-2 border border-dark border-2 bg-primary p-2 text-dark bg-opacity-25">
                <br>
                <h2 style="text-align: center;">ARCHIVO</h2>
                <div class="row-col-6" style="text-align: center;">
                    <br>
                    <a href="./index.php" class="btn btn-primary m-1"> Nuevo archivo</a>
                    <br>
                    <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#filesModal">Abrir archivo</button>
                    <br>
                </div>
                <br>
            </div>
            <!-- Div para el bloc -->
            <div class="col-7 bg-success p-2 text-dark bg-opacity-25">
                <br>
                <?php if ($cargar && !empty($file)) { ?>
                    <h3 style="text-align: right;"><?php echo $file ?></h3>
                    <form class="col-12"  method="POST">
                        <div class="form-floating">
                            <textarea placeholder="Escribe aqui tu texto." class="form-control flex-grow-1 shadow-sm bg-body-tertiary rounded" name="content" style="height: 100px" id="floatingTextarea2"><?php echo file_get_contents($dir . $file); ?></textarea>
                            <label for="floatingTextarea2">Escribe aqui tu texto.</label>
                            <input type="hidden" name="filename" value="<?php echo $file ?>">
                        </div>
                        <br>
                        <div class="row-col-7" style="text-align: right;">
                            <button name="save" type="submit" class="btn btn-success">Guardar</button>
                        </div>
                        <br>
                    </form>
                <?php } else { ?>
                    <form class="col-12" method="POST">
                        <div class="form-floating">
                            <textarea placeholder="Escribe aqui tu texto."class="form-control flex-grow-1 shadow bg-body-secondary rounded" name="content" style="height: 100px" id="floatingTextarea2" ></textarea>
                            <label for="floatingTextarea2">Escribe aqui tu texto.</label>
                            <br>
                            <?php if (!$cargar) { ?>
                                <div class="row-col-7" style="text-align: right;">
                                    <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#saveModal">Guardar archivo</button>
                                </div>
                                <br>
                            <?php } ?>
                        </div>
                        <!-- Modal para guardar un archivo -->
                        <div class="modal fade" id="saveModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="saveModalLabel">Nombre del archivo</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="form-floating m-1">
                                        <input type="text" placeholder="Ingresa el nombre del archivo" class="form-control m-1" id="filename" name="filename" aria-describedby="filename" required>
                                        <label for="filename" class="m-1">Ingresa el nombre del archivo</label>
                                    </div>
                                    <div class="modal-footer">
                                        <button name="save" type="submit" class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>  
             <!-- Modal para abrir un archivo -->
            <div class="modal fade" id="filesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="filesModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="filesModalLabel">Archivos</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="d-flex flex-column p-0" style="gap: 1rem">
                                <?php foreach ($files as $file_item) { ?>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <a href="index.php?file=<?php echo $file_item ?>"><?php echo $file_item ?></a>
                                        <a class="btn btn-danger" href="?<?php if ($file) echo "file=" . $file ?>&delete=<?php echo $file_item ?>">Eliminar</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
    

       

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>