<?php
class LibroRepository
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }

    public function crear(Libro $libro)
    {
        $query = "INSERT INTO libros (libro_titulo, libro_autor, libro_isbn, libro_anio_publicacion, libro_descripcion, libro_portada) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $libro->getTitulo(),
            $libro->getAutor(),
            $libro->getIsbn(),
            $libro->getAnioPublicacion(),
            $libro->getDescripcion(),
            $libro->getPortada()
        ]);
    }

    public function actualizar(Libro $libro)
    {
        // Consulta SQL para actualizar un registro existente en la base de datos.
        $query = "UPDATE libros SET 
            libro_titulo = COALESCE(NULLIF(:titulo, ''), libro_titulo),
            libro_autor = COALESCE(NULLIF(:autor, ''), libro_autor),
            libro_anio_publicacion = COALESCE(NULLIF(:anio_publicacion, ''), libro_anio_publicacion),
            libro_descripcion = COALESCE(NULLIF(:descripcion, ''), libro_descripcion),
            libro_portada = COALESCE(NULLIF(:portada, ''), libro_portada)
          WHERE libro_isbn = :isbn";

        // Preparar la consulta
        $stmt = $this->db->prepare($query);

        // Ejecutar la consulta con los parámetros correctos
        $stmt->execute([
            ':titulo' => $libro->getTitulo(),
            ':autor' => $libro->getAutor(),
            ':anio_publicacion' => $libro->getAnioPublicacion(),
            ':descripcion' => $libro->getDescripcion(),
            ':portada' => $libro->getPortada(),
            ':isbn' => $libro->getIsbn()  // Usamos el ISBN como identificador único para encontrar el libro a actualizar
        ]);
    }

    public function obtenerTodos()
    {
        $query = "SELECT * FROM libros";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerLibroPorIsbn($isbn)
{
    $query = "SELECT * FROM libros WHERE libro_isbn = :isbn";
    $stmt = $this->db->prepare($query);
    $stmt->execute([':isbn' => $isbn]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function borrarLibro($isbn)
    {
        // Consulta SQL para borrar un registro existente en la base de datos.
        $query = "DELETE FROM libros WHERE libro_isbn = :isbn";

        // Preparar la consulta
        $stmt = $this->db->prepare($query);

        // Ejecutar la consulta con el ISBN
        $stmt->execute([':isbn' => $isbn]); // Pasamos el ISBN en un array asociativo
    }
}
