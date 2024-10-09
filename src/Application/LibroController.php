<?php
class LibroController
{
    private $libroRepository;
    private $openLibraryClient;

    public function __construct(LibroRepository $libroRepository, OpenLibraryClient $openLibraryClient)
    {
        $this->libroRepository = $libroRepository;
        $this->openLibraryClient = $openLibraryClient;
    }

    // Método para crear un libro
    public function crearLibro($datos)
    {
        // Crear un nuevo objeto libro con los datos proporcionados
        $libro = new Libro(
            $datos['titulo'],
            $datos['autor'],
            $datos['isbn'],
            $datos['anio_publicacion']
        );

        // Obtener la descripción del libro desde la API
        $descripcion = $this->openLibraryClient->obtenerDescripcionPorISBN($datos['isbn']);
        $portada = $this->openLibraryClient->obtenerPortadaPorISBN($datos['isbn']);
        $libro->setDescripcion($descripcion);
        $libro->setPortada($portada);

        // Guardar el libro en la base de datos
        $this->libroRepository->crear($libro);
    }

    public function obtenerLibroPorIsbn($isbn)
    {
        return $this->libroRepository->obtenerLibroPorIsbn($isbn);
    }

    // Método para actualizar un libro
    public function actualizarLibro($datos)
    {
        // Crear un objeto libro con los datos actualizados
        $libro = new Libro(
            $datos['titulo'],
            $datos['autor'],
            $datos['isbn'],
            $datos['anio_publicacion']
        );

        // Obtener la nueva descripción desde la API
        $descripcion = $this->openLibraryClient->obtenerDescripcionPorISBN($datos['isbn']);
        $portada = $this->openLibraryClient->obtenerPortadaPorISBN($datos['isbn']);

        //Si la descripción ya ha sido modificada y es diferente de la que se recoge automáticamente de la API, se pone la nueva descripción, si es la de la API, se mantiene
        if ($descripcion == $datos['descripcion']) {
            $libro->setDescripcion($descripcion);
        } else {
            $libro->setDescripcion($datos['descripcion']);
        }

        $libro->setPortada($portada);

        // Actualizar el libro en la base de datos
        $this->libroRepository->actualizar($libro);
    }

    // Función para borrar un libro por ISBN
    public function borrarLibro($isbn)
    {
        // Llamar al repositorio para borrar el libro
        $this->libroRepository->borrarLibro($isbn);
    }
}
