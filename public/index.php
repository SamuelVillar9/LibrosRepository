<?php

require_once '../src/Infrastructure/Database.php';
require_once '../src/Infrastructure/LibroRepository.php';
require_once '../src/Infrastructure/OpenLibraryClient.php';
require_once '../src/Application/LibroController.php';
require_once '../src/Domain/Libro.php';

// Inicializar dependencias
$db = new Database('db', 'libros', 'user', 'password'); // Parámetros de la BBDD
$libroRepository = new LibroRepository($db);
$openLibraryClient = new OpenLibraryClient($db);
$libroController = new LibroController($libroRepository, $openLibraryClient);

// Inicializar resultados de búsqueda
$resultados = [];
$libro = null; //Variable para guardar los datos del libro a actualizar

// Gestionar una solicitud para crear un libro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $datos = $_POST;
    $libroController->crearLibro($datos);
}

// Gestionar una solicitud para borrar un libro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar'])) {
    $libroController->borrarLibro($_POST['isbn']);
}

// Gestionar una solicitud de búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $tipo = $_POST['tipo'];
    $termino = trim($_POST['termino']);

    // Realizar la búsqueda según el tipo seleccionado
    if ($tipo === 'autor') {
        $resultados = $openLibraryClient->buscarLibrosPorAutor($termino);
    } elseif ($tipo === 'titulo') {
        $resultados = $openLibraryClient->buscarLibrosPorTitulo($termino);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repositorio de Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        img {
            width: 15%;
            height: 5%;
            object-fit: cover;
            /* Mantener la relación de aspecto */
        }
    </style>
</head>

<body>

    <div class="container">
        <br>
        <div class="row text-center">
            <h1>REPOSITORIO DE LIBROS - SAMUEL VILLAR</h1>
        </div>
        <br>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <h2>Creación de libros</h2>
                <form action="index.php" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Titulo" aria-label="Titulo" id="titulo" name="titulo" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Autor" aria-label="Autor" id="autor" name="autor" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="ISBN" aria-label="ISBN" id="isbn" name="isbn" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Año de publicación" aria-label="Año de publicación" id="anio_publicacion" name="anio_publicacion" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success" name="crear">Crear</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Búsqueda de Libros</h2>
                <form method="POST" action="">
                    <div class="row">
                        <div class="input-group">
                            <select name="tipo" id="tipo" class="form-select" onchange="actualizarPlaceholder()">
                                <option value="titulo" selected>Título</option>
                                <option value="autor">Autor</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="" name="termino" id="termino" required>
                            <button type="submit" class="btn btn-outline-primary" name="buscar">Buscar</button>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])): ?>
                    <h2>Resultados de búsqueda:</h2>
                    <div class="row">

                        <?php if (is_array($resultados) && !empty($resultados)): ?>
                            <?php foreach ($resultados as $libro): ?>

                                <div class="col-md-3">
                                    <div class="card" style="width: 18rem;">
                                        <?php if (!empty($libro['libro_portada'])): ?>
                                            <img class="card-img-top" src="<?php echo htmlspecialchars($libro['libro_portada']); ?>" alt="Portada del libro">
                                        <?php else: ?>
                                            La portada no está disponible
                                        <?php endif; ?><br>
                                        <div class="card-body">
                                            <h5 class="card-title"> <?php echo htmlspecialchars($libro['libro_titulo']); ?></h5>
                                            <p class="card-text"> <?php echo htmlspecialchars($libro['libro_descripcion'] ?? 'La descripción no está disponible'); ?></p>
                                            <p class="card-text">De <span class="fst-italic text-primary"><?php echo htmlspecialchars($libro['libro_autor']); ?></span></p>
                                            <p class="card-text"> Fecha Publicación <span class="badge bg-primary"><?php echo htmlspecialchars($libro['libro_anio_publicacion'] ?? 'No disponible'); ?></span></p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($libro['libro_isbn']); ?>">
                                                        <button type="submit" class="btn btn-warning" name="mostrarFormularioActualizar">Actualizar</button>
                                                    </form>
                                                </div>
                                                <div class="col-md-6">
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($libro['libro_isbn']); ?>">
                                                        <button type="submit" class="btn btn-danger" name="borrar">Borrar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No se encontraron resultados.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <?php
    // Gestionar la solicitud para mostrar el formulario de actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mostrarFormularioActualizar'])) {
        $isbn = $_POST['isbn'];
        $libro = $libroController->obtenerLibroPorISBN($isbn); // Obtener el libro por ISBN

        if ($libro) {
            echo '<div class="container">
                    <h2>Editar Libro</h2>
                        <form method="POST" action="editar_libro.php">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="titulo" name="titulo" value="' . htmlspecialchars($libro['libro_titulo']) . '" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="autor" name="autor" value="' . htmlspecialchars($libro['libro_autor']) . '" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="number" class="form-control" id="anio_publicacion" name="anio_publicacion" value="' . htmlspecialchars($libro['libro_anio_publicacion']) . '" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea id="descripcion" class="form-control" name="descripcion">' . htmlspecialchars($libro['libro_descripcion']) . '</textarea>
                                </div>
                            </div>    
                            <input type="hidden" name="isbn" value="' . htmlspecialchars($libro['portada']) . '">
                            <input type="hidden" name="isbn" value="' . htmlspecialchars($libro['libro_isbn']) . '">
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-warning" name="actualizar">Guardar cambios</button>
                                </div>
                            </div>
                        </div>
                    </form>';
        }
    }
    ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    function actualizarPlaceholder() {
        const tipoSelect = document.getElementById('tipo');
        const terminoInput = document.getElementById('termino');

        // Establecer el nuevo placeholder basado en la opción seleccionada
        if (tipoSelect.value === 'titulo') {
            terminoInput.placeholder = 'Busca por título';
        } else if (tipoSelect.value === 'autor') {
            terminoInput.placeholder = 'Busca por autor';
        }
    }

    // Llamar a la función una vez al cargar la página para establecer el placeholder correcto
    window.onload = actualizarPlaceholder;
</script>

</html>