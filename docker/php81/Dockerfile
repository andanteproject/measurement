# Base stage with common dependencies
FROM php:8.1-cli AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    && docker-php-ext-install intl \
    && docker-php-ext-install bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Development stage (with Xdebug for local development)
FROM base AS dev

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Xdebug configuration
RUN echo "xdebug.mode=coverage,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

CMD ["sleep", "infinity"]

# CI stage (with PCOV for faster coverage in CI)
FROM base AS ci

# Install PCOV for code coverage (faster than Xdebug for CI)
RUN pecl install pcov \
    && docker-php-ext-enable pcov

CMD ["sleep", "infinity"]
