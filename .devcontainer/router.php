<?php
// Roteador do servidor embutido do PHP (usado só no Codespaces):
// ao abrir a raiz do site, redireciona para o processos.php.
if (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) === "/") {
    header("Location: /processos.php");
    exit;
}

return false; // demais arquivos são servidos normalmente
