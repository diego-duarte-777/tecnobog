# Imagen base oficial con PHP y extensiones necesarias
FROM php:8.2-apache

# Instalar dependencias para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copiar el código de tu proyecto al contenedor
COPY . /var/www/html/

# Configuración de Apache
EXPOSE 10000
CMD ["apache2-foreground"]
