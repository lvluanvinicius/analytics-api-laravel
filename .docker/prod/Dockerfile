FROM php:8.2-fpm

# Diretório de trabalho
WORKDIR /var/www/html

# Instalação de dependências
RUN apt-get update && apt-get install -y \
    # Dependências do PHP
    libmcrypt-dev \
    libmagickwand-dev \
    libzip-dev \
    # Outras dependências úteis
    git \
    zip \
    unzip\
    redis

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
    php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" &&\
    php composer-setup.php &&\
    php -r "unlink('composer-setup.php');" &&\
    mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo_pgsql zip exif pcntl

EXPOSE 10555

CMD ["php-fpm"]
