FROM php:8.3-cli

WORKDIR /app

COPY . /app

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

# Add command task-cli to PATH
RUN chmod +x /app/task-cli && ln -s /app/task-cli /usr/local/bin/task-cli

RUN php -v && composer --version
