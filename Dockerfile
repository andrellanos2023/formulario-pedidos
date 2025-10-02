# Imagen base de PHP con Apache
FROM php:8.1-apache

# Instala extensiones necesarias (PostgreSQL + MySQL por si acaso)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql

# Copia el proyecto dentro del contenedor
COPY . /var/www/html/

# Da permisos a la carpeta
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto de Apache
EXPOSE 80