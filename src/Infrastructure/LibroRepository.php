class LibroRepository {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getConnection();
    }

    public function crear(Libro $libro) {
        // Implementar lógica de inserción
    }

    public function obtenerTodos() {
        // Implementar lógica para obtener todos los libros
    }

    public function actualizar(Libro $libro) {
        // Implementar lógica para actualizar un libro
    }

    public function eliminar($isbn) {
        // Implementar lógica para eliminar un libro por ISBN
    }
}
