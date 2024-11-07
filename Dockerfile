# Use a imagem oficial do PHP como base
FROM php:8.1-apache

# Instale as extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Copie os arquivos do seu projeto para o diretório do Apache
COPY . /var/www/html/

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Defina as permissões (opcional)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080