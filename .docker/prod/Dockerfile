# Define a imagem base para o PHP
FROM php:8.2-apache


# Copie o arquivo de configuração do sources.list para o container
RUN apt update && apt install -y cron iputils-ping

# Instala as dependências necessárias para o PHP e o Node.js
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql zip sockets

# Instala o Node.js v19.9.0
RUN curl -fsSL https://deb.nodesource.com/setup_19.x | bash - \
    && apt-get install -y nodejs

# Define o diretório de trabalho dentro do container
WORKDIR /var/www/html

# Copia os arquivos do projeto para o diretório de trabalho
COPY . /var/www/html

# Instala as dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Executa o Composer para instalar as dependências do Laravel
RUN composer install --no-interaction

# Cria um link entre public e arquivo publico de imagem
RUN php artisan storage:link

# Gera a chave do Laravel
RUN php artisan key:generate

# Adiciona o comando ao crontab
RUN echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" >> /etc/crontab

# Executa o comando para atualizar o crontab
RUN crontab /etc/crontab

# Instala as dependências do npm
RUN npm install

# Executa o build com o Vite e copia os arquivos estáticos
RUN npm run build

# Configura as permissões de escrita no diretório storage e bootstrap/cache
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Copia o arquivo de configuração do Apache
COPY .docker/prod/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Ativa o módulo de reescrita do Apache
RUN a2enmod rewrite

# Define a porta que o container deve expor
EXPOSE 80

# Inicializa o Apache quando o container for iniciado
CMD ["apache2-foreground"]
