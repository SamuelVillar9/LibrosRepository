<?php

namespace App\Tests\Application; // Asegúrate de que el espacio de nombres sea correcto

use PHPUnit\Framework\TestCase;
use App\Application\LibroController;
use App\Domain\Libro;
use App\Infrastructure\LibroRepository; // Importar el repositorio
use App\Infrastructure\OpenLibraryClient; // Importar el cliente

class LibroControllerTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        $this->controller = new LibroController();
    }

    public function testCreateLibro()
    {
        $libro = new Libro('Título', 'Autor', 2024);
        $result = $this->controller->create($libro);
        $this->assertTrue($result);
    }

    public function testEditLibro()
    {
        $libro = new Libro('Título', 'Autor', 2024);
        $this->controller->create($libro);
        $libro->setAutor('Nuevo Autor');
        $result = $this->controller->edit($libro);
        $this->assertTrue($result);
    }

    public function testDeleteLibro()
    {
        $libro = new Libro('Título', 'Autor', 2024);
        $this->controller->create($libro);
        $result = $this->controller->delete($libro->getId());
        $this->assertTrue($result);
    }
}
