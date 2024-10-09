# LibrosRepository

## Descripción

Este proyecto es una aplicación de gestión de libros que utiliza una API externa. Se centra en implementar principios avanzados de desarrollo para ofrecer una experiencia de usuario óptima y un manejo eficiente de los datos.

## Características

- Integración con una API externa para la gestión de libros.
- Código estructurado siguiendo principios de desarrollo moderno.
- Uso de PHP y Docker para el desarrollo y despliegue.

## Tecnologías

- PHP
- Docker
- Composer

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/SamuelVillar9/LibrosRepository.git

2. Navega al directorio del proyecto:
    cd LibrosRepository

3. Construye los contenedores de Docker:
    docker-compose up -d

## public/index.php
El archivo index.php en tu repositorio se encarga de gestionar la interacción con la API externa para obtener y mostrar información sobre libros. Se configura la conexión con la API, se procesan las solicitudes y se manejan las respuestas para presentar los datos al usuario. Además, se establecen las rutas necesarias y se asegura que las operaciones de CRUD se realicen correctamente. 
Si deseas más detalles específicos, revisa el código directamente en el repositorio.

## public/editar_libro.php
El archivo editar_libro.php permite editar la información de un libro específico. Al cargar, verifica si se ha enviado un ID de libro para cargar los datos actuales. Si se envía el formulario de edición, recopila la nueva información y realiza una solicitud a la API externa para actualizar el libro. Se maneja la validación de datos y se redirige al usuario a la vista de detalles del libro una vez que se completa la actualización.
Para más detalles, puedes consultar el código en el repositorio.

## src/Application/LibroController.php
El archivo src/Application/LibroController.php gestiona la lógica del controlador para las operaciones relacionadas con los libros. Define métodos para crear, leer, actualizar y eliminar libros, interactuando con el modelo y la API externa. También maneja las validaciones de datos y la redirección a las vistas apropiadas, asegurando que las operaciones CRUD se realicen de manera efectiva. 
Para más detalles, puedes revisar el código en el repositorio.

## src/Domain/Libro.php
El archivo src/Domain/Libro.php define la clase Libro, que representa la entidad del libro en la aplicación. Esta clase contiene las propiedades del libro, como el título, autor y año de publicación, así como métodos para acceder y modificar estos datos. También puede incluir validaciones y lógica relacionada con las operaciones del libro, asegurando que los datos sean coherentes y válidos en toda la aplicación.
Para más detalles, revisa el código en el repositorio.

## src/Infrastructure/Database.php
El archivo src/Infrastructure/Database.php se encarga de gestionar la conexión a la base de datos. Define métodos para realizar consultas y operaciones de base de datos, como insertar, actualizar y eliminar registros. Este archivo actúa como intermediario entre la aplicación y la base de datos, asegurando que las solicitudes de datos se manejen de manera eficiente y segura.
Para más detalles, puedes consultar el código en el repositorio.

## src/Infrastructure/LibroRepository.php
El archivo src/Infrastructure/LibroRepository.php implementa el repositorio para la entidad Libro, proporcionando métodos para interactuar con la base de datos relacionados con las operaciones CRUD. Actúa como una capa de acceso a datos, facilitando la recuperación, almacenamiento y manipulación de información de libros en la base de datos. Este enfoque ayuda a separar la lógica de negocio de la lógica de acceso a datos, manteniendo un código más limpio y modular.
Para más detalles, puedes consultar el código en el repositorio.

## src/Infrastructure/OpenLibraryClient.php
El archivo src/Infrastructure/OpenLibraryClient.php actúa como un cliente para interactuar con la API de Open Library. Proporciona métodos para realizar solicitudes a la API, permitiendo buscar libros, obtener información detallada y manejar respuestas. Este cliente facilita la comunicación entre la aplicación y la API externa, encapsulando la lógica de conexión y manejo de datos, lo que permite que el resto de la aplicación se enfoque en la lógica de negocio.
Para más detalles, consulta el código en el repositorio.