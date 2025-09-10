# Imagen base con PHP + Apache
FROM php:8.2-apache

# Instalar extensiones de PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copiar el código del proyecto
COPY . /var/www/html/

# Configurar Apache para que use index.php como entrada
RUN echo "<Directory /var/www/html/> \
    AllowOverride All \
    Require all granted \
</Directory>" > /etc/apache2/conf-available/php.conf \
    && echo "DirectoryIndex index.php index.html" >> /etc/apache2/conf-available/php.conf \
    && a2enconf php

# Exponer puerto que Render espera
EXPOSE 10000

# Iniciar Apache
CMD ["apache2-foreground"]
