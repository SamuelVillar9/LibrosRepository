<?php
class Database
{
    private $pdo;

    public function __construct($host, $dbname, $username, $password)
    {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        try {
            // Establecer conexión PDO
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurar para que lance excepciones
        } catch (PDOException $e) {
            // Manejo de excepciones en la conexión
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo; // Devolver la conexión PDO
    }
}
