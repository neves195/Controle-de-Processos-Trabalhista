# Ambiente do Codespaces: PHP 8.3 + MariaDB (compatível com o MySQL do XAMPP)
FROM mcr.microsoft.com/devcontainers/php:1-8.3-bookworm

# Instala o servidor de banco de dados
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
       mariadb-server mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Habilita a extensão mysqli usada pelo processos.php
RUN docker-php-ext-install mysqli

# Faz o mysqli_connect("localhost", ...) encontrar o socket do MariaDB
RUN echo "mysqli.default_socket=/run/mysqld/mysqld.sock" \
    > /usr/local/etc/php/conf.d/mysqli-socket.ini
