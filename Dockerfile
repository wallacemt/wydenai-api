# Use a imagem oficial do PHP como base
FROM php:8.0-apache

# Instale as extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Copie os arquivos do seu projeto para o diretório do Apache
COPY . /var/www/html/

# Defina as permissões (opcional)
RUN chown -R www-data:www-data /var/www/html