# Imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalar extensiones necesarias (pgsql para PostgreSQL)
RUN docker-php-ext-install pgsql pdo_pgsql

# Copiar archivos del proyecto a la carpeta pública de Apache
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]

