use PHPUnit\Framework\TestCase;

class LibroTest extends TestCase {
    public function testCrearLibro() {
        $libro = new Libro("Título", "Autor", "123456789", 2024, "Descripción");
        $this->assertEquals("Título", $libro->getTitulo());
    }
}
