<?php

class Logger
{
    private $logFile;

    public function __construct($logFile = 'app.log')
    {
        // Definir la ruta de la carpeta Logs en la raíz del proyecto
        $logDirectory = __DIR__ . '/../../Logs';

        // Crear la carpeta Logs si no existe
        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0755, true); // Crear la carpeta con permisos 0755
        }

        // Definir la ruta completa del archivo de log
        $this->logFile = $logDirectory . '/' . $logFile;
    }

    public function log($message)
    {
        // Obtener la fecha y hora actual
        $timestamp = date("Y-m-d H:i:s");

        // Formatear el mensaje de log
        $formattedMessage = "[$timestamp] $message" . PHP_EOL;

        // Guardar el mensaje en el archivo de log
        file_put_contents($this->logFile, $formattedMessage, FILE_APPEND);
    }
}
