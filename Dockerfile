# Imagen base de PHP con Apache
FROM php:8.1-apache

# Instala extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copia el proyecto dentro del contenedor
COPY . /var/www/html/

# Da permisos a la carpeta
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto de Apache
EXPOSE 80
