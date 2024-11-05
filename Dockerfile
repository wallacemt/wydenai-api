# Use a imagem oficial do PHP como base
FROM php:8.1-apache

# Instale as extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli mbstring

# Instale o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure o Apache (caso necessário)
RUN echo 'DocumentRoot /var/www/html/public' > /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copie os arquivos do seu projeto para o diretório do Apache
COPY . /var/www/html/

# Defina as permissões (opcional)
RUN chown -R www-data:www-data /var/www/html

# Exponha a porta do Apache
EXPOSE 80
