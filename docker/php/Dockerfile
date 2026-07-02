FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    gnupg2 \
    apt-transport-https \
    build-essential \
    unixodbc \
    unixodbc-dev \
    libzip-dev \
    libicu-dev

# Add Microsoft package repository
# RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc \
#     | gpg --dearmor \
#     -o /usr/share/keyrings/microsoft-prod.gpg && \
#     echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" \
#     > /etc/apt/sources.list.d/mssql-release.list
RUN ARCH=$(dpkg --print-architecture) && \
    curl -sSL https://packages.microsoft.com/keys/microsoft.asc \
    | gpg --dearmor \
    -o /usr/share/keyrings/microsoft-prod.gpg && \
    echo "deb [arch=${ARCH} signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" \
    > /etc/apt/sources.list.d/mssql-release.list

# Install Microsoft ODBC Driver
RUN apt-get update && \
    ACCEPT_EULA=Y apt-get install -y \
    msodbcsql18 \
    mssql-tools18

# Install PHP SQL Server extension
RUN pecl install sqlsrv pdo_sqlsrv && \
    docker-php-ext-enable sqlsrv pdo_sqlsrv

RUN docker-php-ext-install zip intl

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf