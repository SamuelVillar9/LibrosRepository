<?php
class Libro {
    private $titulo;
    private $autor;
    private $isbn;
    private $anio_publicacion;
    private $descripcion;
    private $portada;

    public function __construct($titulo, $autor, $isbn, $anio_publicacion, $descripcion = null, $portada = null) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->isbn = $isbn;
        $this->anio_publicacion = $anio_publicacion;
        $this->descripcion = $descripcion;
        $this->portada = $portada;
    }

    // Métodos getters y setters
    public function getTitulo() {
        return $this->titulo;
    }
    public function getAutor() {
        return $this->autor;
    }
    public function getIsbn() {
        return $this->isbn;
    }
    public function getAnioPublicacion() {
        return $this->anio_publicacion;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getPortada() {
        return $this->portada;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    public function setPortada($portada) {
        $this->portada = $portada;
    }

}
