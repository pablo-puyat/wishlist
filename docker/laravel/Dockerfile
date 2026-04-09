FROM dunglas/frankenphp:php8.5-bookworm

ARG USER_ID=1000
ARG COMPOSER_ALLOW_SUPERUSER=1

# Setup user and install PHP extensions
RUN usermod --uid ${USER_ID} www-data \
    && install-php-extensions pdo pdo_pgsql mbstring exif pcntl bcmath gd zip redis

# Install System dependencies and Node.js 22 directly
RUN apt-get update && apt-get install -y \
    git entr unrar-free unzip curl \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
