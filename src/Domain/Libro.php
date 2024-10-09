// src/Domain/Libro.php
class Libro {
    private $titulo;
    private $autor;
    private $isbn;
    private $anio_publicacion;
    private $descripcion;

    public function __construct($titulo, $autor, $isbn, $anio_publicacion, $descripcion = null) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->isbn = $isbn;
        $this->anio_publicacion = $anio_publicacion;
        $this->descripcion = $descripcion;
    }

    // Métodos getters y setters
    public function getTitulo() {
        return $this->titulo;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    // Otros métodos relacionados a la lógica de negocio del libro
}
