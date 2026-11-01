FROM php:8.2-apache

# Habilitar el módulo de reescritura de Apache (necesario para enrutadores como Flight)
RUN a2enmod rewrite

# Cambiar la raíz del servidor a la carpeta 'public'
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copiar todos los archivos del proyecto al contenedor
COPY . /var/www/html/

# Dar permisos correctos a los archivos
RUN chown -R www-data:www-data /var/www/html