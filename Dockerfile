# Use a imagem oficial do PHP como base
FROM php:8.1-apache

# Instale as extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Copie os arquivos do diretório htdocs para o diretório do Apache
COPY htdocs/ /var/www/html/

# Defina as permissões (opcional)
RUN chown -R www-data:www-data /var/www/html

# Exponha a porta do Apache
EXPOSE 80
