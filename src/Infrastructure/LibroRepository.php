<?php

require_once 'Logger.php';
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

        $logger = new Logger();

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $libro->getTitulo(),
                $libro->getAutor(),
                $libro->getIsbn(),
                $libro->getAnioPublicacion(),
                $libro->getDescripcion(),
                $libro->getPortada()
            ]);

            $logger->log("############## CREANDO LIBRO ##############");
            $logger->log(" TITULO= " . $libro->getTitulo() . " - AUTOR= " . $libro->getAutor() . " - ISBN= " . $libro->getIsbn() . " - AÑO PUBLICACIÓN= " . $libro->getAnioPublicacion() . " - Descripcion= " . $libro->getDescripcion() . " - PORTADA= " . $libro->getPortada()); // Registrar el evento
            $logger->log("############## LIBRO CREADO ##############");
        } catch (PDOException $e) {
            $logger->log("############## ERROR CREANDO LIBRO ##############");
            $logger->log("Error al crear libro: " . $e->getMessage()); // Registrar el error
            $logger->log("############################");
            return ['success' => false, 'message' => 'Error al crear el libro: ' . $e->getMessage()];
        }
    }

    public function actualizar(Libro $libro)
    {
        $logger = new Logger();

        // Consulta SQL para actualizar un registro existente en la base de datos.
        $query = "UPDATE libros SET 
            libro_titulo = COALESCE(NULLIF(:titulo, ''), libro_titulo),
            libro_autor = COALESCE(NULLIF(:autor, ''), libro_autor),
            libro_anio_publicacion = COALESCE(NULLIF(:anio_publicacion, ''), libro_anio_publicacion),
            libro_descripcion = COALESCE(NULLIF(:descripcion, ''), libro_descripcion),
            libro_portada = COALESCE(NULLIF(:portada, ''), libro_portada)
          WHERE libro_isbn = :isbn";

        try {
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

            $logger->log("############## ACTUALIZANDO LIBRO ##############");
            $logger->log(" TITULO= " . $libro->getTitulo() . " - AUTOR= " . $libro->getAutor() . " - ISBN= " . $libro->getIsbn() . " - AÑO PUBLICACIÓN= " . $libro->getAnioPublicacion() . " - DESCRIPCIÓN= " . $libro->getDescripcion() . " - PORTADA= " . $libro->getPortada()); // Registrar el evento
            $logger->log("############## LIBRO ACTUALIZADO ##############");
        } catch (PDOException $e) {
            $logger->log("############## ERROR ACTUALIZANDO LIBRO ##############");
            $logger->log("Error al actualizar libro: " . $e->getMessage()); // Registrar el error
            $logger->log("############################");
            return ['success' => false, 'message' => 'Error al actualizar el libro: ' . $e->getMessage()];
        }
    }

    public function obtenerTodos()
    {
        $query = "SELECT * FROM libros";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerLibroPorIsbn($isbn)
    {
        $logger = new Logger();
        $query = "SELECT * FROM libros WHERE libro_isbn = :isbn";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':isbn' => $isbn]);

            $logger->log("############## OBTENIENDO TODOS LOS LIBROS  ##############");
            $logger->log("############################");

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $logger->log("############## ERROR OBTENIENDO LOS LIBROS ##############");
            $logger->log("Error al obtener los libros: " . $e->getMessage()); // Registrar el error
            $logger->log("############################");
            return ['success' => false, 'message' => 'Error al obtener los libros: ' . $e->getMessage()];
        }
    }

    public function borrarLibro($isbn)
    {
        $logger = new Logger();
        // Consulta SQL para borrar un registro existente en la base de datos.
        $query = "DELETE FROM libros WHERE libro_isbn = :isbn";

        try {

            $logger->log("############## BORRANDO LIBRO ##############");
            $logger->log("ISBN LIBRO BORRADO: " . $isbn); // Registrar el evento
            $logger->log("############## LIBRO BORRADO ##############");

            // Preparar la consulta
            $stmt = $this->db->prepare($query);
            // Ejecutar la consulta con el ISBN
            $stmt->execute([':isbn' => $isbn]); // Pasamos el ISBN en un array asociativo
            

        } catch (PDOException $e) {
            $logger->log("############## ERROR BORRANDO EL LIBROS ##############");
            $logger->log("Error al borrar el libros: " . $e->getMessage()); // Registrar el error
            $logger->log("############################");
            return ['success' => false, 'message' => 'Error al borrar el libros: ' . $e->getMessage()];
        }
    }
}
