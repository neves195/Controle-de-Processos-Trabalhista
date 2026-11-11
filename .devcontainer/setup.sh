#!/usr/bin/env bash
# Executado uma vez, quando o Codespace é criado.
set -e

echo "==> Iniciando o MariaDB..."
sudo service mariadb start

for i in $(seq 1 30); do
    sudo mariadb -e "SELECT 1" > /dev/null 2>&1 && break
    sleep 1
done

echo "==> Configurando o usuário root sem senha (igual ao XAMPP)..."
sudo mariadb -e "ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD(''); FLUSH PRIVILEGES;"

echo "==> Importando o banco.sql..."
mariadb -u root --default-character-set=utf8mb4 < banco.sql

echo "==> Banco processostrabalhistas pronto."
