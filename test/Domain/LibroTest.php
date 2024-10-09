<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Libro;

class LibroTest extends TestCase
{
    public function testGettersSetters()
    {
        $libro = new Libro('Título', 'Autor', 2024);
        $this->assertEquals('Título', $libro->getTitulo());
        $this->assertEquals('Autor', $libro->getAutor());
        $this->assertEquals(2024, $libro->getAnio());
    }

    public function testInvalidData()
    {
        $this->expectException(InvalidArgumentException::class);
        new Libro('', '', 2024); // Título y Autor vacíos
    }
}
