# Imagen base con PHP y Apache
FROM php:8.2-apache

# Instalar extensiones necesarias de PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copiar solo los archivos de pruebas_php/ a la raíz de Apache
COPY pruebas_php/ /var/www/html/

# Asegurar que index.php sea el archivo de inicio
RUN echo 'DirectoryIndex index.php index.html' > /etc/apache2/conf-available/docker.conf \
    && a2enconf docker

# Exponer el puerto que Render espera
EXPOSE 10000

# Iniciar Apache
CMD ["apache2-foreground"]
