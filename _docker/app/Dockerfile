FROM php:8.2-fpm

# Установка необходимых пакетов и расширений PHP
RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip \
      unzip \
      git \
      supervisor \
      nodejs \
      npm && \
      docker-php-ext-install pdo_mysql bcmath gd zip && \
      pecl install igbinary && \
      docker-php-ext-enable igbinary && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Установка Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

# Копирование конфигурации PHP и Supervisord
COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./_docker/app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Установка рабочей директории
WORKDIR /var/www

# Открытие порта для приложения
EXPOSE 5173
EXPOSE 9001

# Запуск Supervisord по умолчанию
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
