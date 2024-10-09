# Utiliza una imagen base de PHP con Apache
FROM php:7.4-apache

# Instala extensiones necesarias de PHP, como PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Composer desde la imagen oficial de Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Habilitar módulos de Apache si es necesario (mod_rewrite, por ejemplo)
RUN a2enmod rewrite

# Configura los permisos adecuados para la carpeta de trabajo
RUN chown -R www-data:www-data /var/www/html

# Copia los archivos de la aplicación en el contenedor
COPY . /var/www/html

# Exponer el puerto 80 para que la aplicación sea accesible
EXPOSE 80

# Ejecuta Composer para instalar las dependencias
WORKDIR /var/www/html
RUN composer install

# Instrucción CMD para iniciar Apache
CMD ["apache2-foreground"]
