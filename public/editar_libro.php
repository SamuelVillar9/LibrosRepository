<?php
require_once '../src/Application/LibroController.php';
require_once '../src/Infrastructure/Database.php';
require_once '../src/Infrastructure/LibroRepository.php';
require_once '../src/Infrastructure/OpenLibraryClient.php';
require_once '../src/Domain/Libro.php';

// Inicializar dependencias
$db = new Database('db', 'libros', 'user', 'password'); // Asegúrate de que estos parámetros son correctos
$libroRepository = new LibroRepository($db);
$openLibraryClient = new OpenLibraryClient($db); // Pasar la instancia de Database aquí
$libroController = new LibroController($libroRepository, $openLibraryClient);

// Gestionar una solicitud para actualizar un libro tras el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $datos = $_POST;
    $libroController->actualizarLibro($datos);
    
    // Redirigir al index
    header('Location: index.php');
    exit;
}
