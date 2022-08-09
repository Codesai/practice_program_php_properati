FROM composer:latest
RUN install-php-extensions xdebug-3.1.5 && docker-php-ext-enable xdebug
ENV XDEBUG_MODE=coverage
WORKDIR /app