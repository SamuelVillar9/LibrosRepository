<?php

class OpenLibraryClient
{
    private $db;

    // Constructor que acepta una instancia de Database
    public function __construct($db)
    {
        $this->db = $db; // Guardar la conexión a la base de datos
    }

    // Método para buscar libros por autor en la base de datos
    public function buscarLibrosPorAutor($autor)
    {
        // Preparar la consulta SQL
        $query = "SELECT * FROM libros WHERE libro_autor LIKE :autor";
        $stmt = $this->db->getConnection()->prepare($query); // Usar el método getConnection

        // Bind de los parámetros
        $stmt->execute(['autor' => "%$autor%"]); // Uso de % para permitir coincidencias parciales

        // Obtener los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array asociativo
    }

    // Método para buscar libros por título en la base de datos
    public function buscarLibrosPorTitulo($titulo)
    {
        // Preparar la consulta SQL
        $query = "SELECT * FROM libros WHERE libro_titulo LIKE :titulo";
        $stmt = $this->db->getConnection()->prepare($query); // Usar el método getConnection

        // Bind de los parámetros
        $stmt->execute(['titulo' => "%$titulo%"]); // Uso de % para permitir coincidencias parciales

        // Obtener los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array asociativo
    }

    // Obtener la descripción de un libro por su ISBN
    public function obtenerDescripcionPorISBN($isbn)
    {
        // Inicializar cURL
        $ch = curl_init();

        // Configurar la URL y parámetros de la solicitud
        curl_setopt($ch, CURLOPT_URL, 'https://openlibrary.org/api/books?bibkeys=ISBN:' . $isbn . '&format=json&jscmd=data');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Para que devuelva el resultado como cadena

        // Ejecutar la solicitud y obtener la respuesta
        $respuesta = curl_exec($ch);

        // Comprobar si ocurrió un error
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return 'Error al obtener la descripción: ' . $error_msg;
        }

        // Obtener el código de estado HTTP
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Cerrar la sesión cURL
        curl_close($ch);

        // Verificar el código de estado HTTP
        if ($http_code !== 200) {
            return 'Error HTTP: ' . $http_code . ' - No se pudo obtener la descripción del libro.';
        }

        // Decodificar la respuesta JSON
        $data = json_decode($respuesta, true);

        // Verificar si la información está disponible y devolverla
        if (isset($data["ISBN:$isbn"])) {
            return $data["ISBN:$isbn"]['notes'] ?? 'Descripción no disponible';
        } else {
            return 'Descripción no disponible';
        }
    }

    // Obtener la URL de la portada de un libro por su ISBN
    public function obtenerPortadaPorISBN($isbn)
    {
        // Inicializar cURL
        $ch = curl_init();

        // Configurar la URL y parámetros de la solicitud
        curl_setopt($ch, CURLOPT_URL, 'https://openlibrary.org/api/books?bibkeys=ISBN:' . $isbn . '&format=json&jscmd=data');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Para que devuelva el resultado como cadena

        // Ejecutar la solicitud y obtener la respuesta
        $respuesta = curl_exec($ch);

        // Comprobar si ocurrió un error
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return 'Error al obtener la portada: ' . $error_msg;
        }

        // Obtener el código de estado HTTP
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Cerrar la sesión cURL
        curl_close($ch);

        // Verificar el código de estado HTTP
        if ($http_code !== 200) {
            return 'Error HTTP: ' . $http_code . ' - No se pudo obtener la portada del libro.';
        }

        // Decodificar la respuesta JSON
        $data = json_decode($respuesta, true);

        // Verificar si la portada está disponible y devolverla
        if (isset($data["ISBN:$isbn"]['cover'])) {
            return $data["ISBN:$isbn"]['cover']['large'] ?? 'Portada no disponible';
        } else {
            return '';
        }
    }
}
