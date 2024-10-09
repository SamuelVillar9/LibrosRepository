<?php

require_once '../src/Infrastructure/Database.php';
require_once '../src/Infrastructure/LibroRepository.php';
require_once '../src/Infrastructure/OpenLibraryClient.php';
require_once '../src/Application/LibroController.php';
require_once '../src/Domain/Libro.php';

// Inicializar dependencias
$db = new Database('db', 'libros', 'user', 'password'); // Asegúrate de que estos parámetros son correctos
$libroRepository = new LibroRepository($db);
$openLibraryClient = new OpenLibraryClient($db); // Pasar la instancia de Database aquí
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
    <style>
        img {
            width: 200px;
            /* Ancho fijo */
            height: 300px;
            /* Alto fijo */
            object-fit: cover;
            /* Mantener la relación de aspecto */
        }
    </style>
</head>

<body>
    <form action="index.php" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" required><br><br>

        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" required><br><br>

        <label for="anio_publicacion">Año de Publicación:</label>
        <input type="number" id="anio_publicacion" name="anio_publicacion" required><br><br>

        <input type="submit" name="crear" value="Crear Libro">
    </form>

    <h1>Búsqueda de Libros</h1>
    <form method="POST" action="">
        <label for="tipo">Buscar por:</label>
        <select name="tipo" id="tipo">
            <option value="autor">Autor</option>
            <option value="titulo">Título</option>
        </select>

        <label for="termino">Ingrese su búsqueda:</label>
        <input type="text" name="termino" id="termino" required>

        <button type="submit" name="buscar">Buscar</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])): ?>
        <h2>Resultados de búsqueda:</h2>
        <?php if (is_array($resultados) && !empty($resultados)): ?>
            <?php foreach ($resultados as $libro): ?>

                <div>
                    <strong>Título:</strong> <?php echo htmlspecialchars($libro['libro_titulo']); ?><br>
                    <strong>Autor(es):</strong> <?php echo htmlspecialchars($libro['libro_autor']); ?><br>
                    <strong>Año de publicación:</strong> <?php echo htmlspecialchars($libro['libro_anio_publicacion'] ?? 'No disponible'); ?><br>
                    <strong>Descripción:</strong> <?php echo htmlspecialchars($libro['libro_descripcion'] ?? 'No disponible'); ?><br>
                    <strong>Portada</strong>:<br>
                    <?php if (!empty($libro['libro_portada'])): ?>
                        <img src="<?php echo htmlspecialchars($libro['libro_portada']); ?>" alt="Portada del libro">
                    <?php else: ?>
                        No disponible
                    <?php endif; ?><br>

                    <form method="POST" action="">
                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($libro['libro_isbn']); ?>">
                        <button type="submit" name="mostrarFormularioActualizar">Actualizar Libro</button>
                    </form>
                    <form method="POST" action="">
                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($libro['libro_isbn']); ?>">
                        <button type="submit" name="borrar">Borrar libro</button>
                    </form>

                </div>
                <hr>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    // Gestionar la solicitud para mostrar el formulario de actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mostrarFormularioActualizar'])) {
        $isbn = $_POST['isbn'];
        $libro = $libroController->obtenerLibroPorISBN($isbn); // Obtener el libro por ISBN

        if ($libro) {
            echo '<h2>Editar Libro</h2>
        <form method="POST" action="editar_libro.php">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="' . htmlspecialchars($libro['libro_titulo']) . '" required><br><br>
            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" value="' . htmlspecialchars($libro['libro_autor']) . '" required><br><br>
            <label for="anio_publicacion">Año de Publicación:</label>
            <input type="number" id="anio_publicacion" name="anio_publicacion" value="' . htmlspecialchars($libro['libro_anio_publicacion']) . '" required><br><br>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion">' . htmlspecialchars($libro['libro_descripcion']) . '</textarea><br><br>
            <input type="hidden" name="isbn" value="' . htmlspecialchars($libro['portada']) . '">
            <input type="hidden" name="isbn" value="' . htmlspecialchars($libro['libro_isbn']) . '">
            <button type="submit" name="actualizar">Guardar cambios</button>
        </form>';
        }
    }
    ?>


</body>

</html>