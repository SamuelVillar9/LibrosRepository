<?php

use PHPUnit\Framework\TestCase;
use App\Infrastructure\LibrosRepository;
use App\Domain\Libro;

class LibrosRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        $this->repository = new LibrosRepository();
    }

    public function testSaveLibro()
    {
        $libro = new Libro('Título', 'Autor', 2024);
        $this->repository->save($libro);
        $this->assertNotNull($libro->getId());
    }

    public function testFindLibro()
    {
        $libro = new Libro('Título', 'Autor', 2024);
        $this->repository->save($libro);
        $foundLibro = $this->repository->find($libro->getId());
        $this->assertEquals($libro->getTitulo(), $foundLibro->getTitulo());
    }

    public function testDeleteLibro()
    {
        $libro = new Libro('Título', 'Autor', 2024);
        $this->repository->save($libro);
        $this->repository->delete($libro->getId());
        $foundLibro = $this->repository->find($libro->getId());
        $this->assertNull($foundLibro);
    }
}
