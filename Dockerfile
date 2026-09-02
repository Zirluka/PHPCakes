FROM php:8.2-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Включаем буферизацию вывода, чтобы работали перенаправления header()
RUN echo "output_buffering = On" > /usr/local/etc/php/conf.d/output-buffering.ini
