# Imagen base con PHP + Apache
FROM php:8.1-apache

# Instalar dependencias necesarias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Copiar todo el proyecto a la carpeta de Apache
COPY . /var/www/html/

# Cambiar dueño de los archivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configuración para que Apache reconozca index.php
RUN a2enmod rewrite
RUN echo "<Directory /var/www/html/> \n\
    Options Indexes FollowSymLinks \n\
    AllowOverride All \n\
    Require all granted \n\
    DirectoryIndex index.php index.html \n\
</Directory>" > /etc/apache2/conf-available/project.conf \
    && a2enconf project

# Puerto de Apache
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
