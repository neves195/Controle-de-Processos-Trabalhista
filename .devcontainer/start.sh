#!/usr/bin/env bash
# Executado sempre que o Codespace liga.

# Sobe o banco (não faz nada se já estiver rodando)
sudo service mariadb start

# Sobe o servidor PHP na porta 8000, se ainda não estiver rodando
if ! pgrep -f "php -S 0.0.0.0:8000" > /dev/null; then
    setsid nohup php -S 0.0.0.0:8000 -t "$PWD" .devcontainer/router.php \
        > /tmp/servidor-php.log 2>&1 < /dev/null &
fi

echo "==> Sistema disponível na porta 8000 (aba PORTS)."
