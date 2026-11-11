# Sistema de Controle de Processos Trabalhistas

## Importando o banco no XAMPP

O script `banco.sql` cria o banco `processostrabalhistas`, recria a tabela `processos` do zero e insere 3 processos de exemplo. Ele pode ser executado quantas vezes for necessário.

Antes de importar, inicie o **MySQL** no painel do XAMPP.

### Pelo phpMyAdmin

1. Acesse <http://localhost/phpmyadmin>.
2. Clique na aba **Importar** (não é preciso selecionar um banco antes, o script já cria e seleciona o banco).
3. Em **Arquivo a importar**, escolha o `banco.sql`. Mantenha o conjunto de caracteres **utf-8**.
4. Clique em **Importar**.

### Pela linha de comando

Na raiz do projeto:

```bash
# Linux
/opt/lampp/bin/mysql -u root --default-character-set=utf8mb4 < banco.sql

# Windows
C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 < banco.sql
```

Se o `mysql` estiver no PATH, basta `mysql -u root --default-character-set=utf8mb4 < banco.sql`.

## Credenciais de conexão (ambiente local)

| Parâmetro | Valor                   |
|-----------|-------------------------|
| Host      | `localhost`             |
| Usuário   | `root`                  |
| Senha     | *(vazia)*               |
| Banco     | `processostrabalhistas` |

Essas são as credenciais padrão do XAMPP e servem apenas para desenvolvimento local.

## Rodando no GitHub Codespaces (sem instalar o XAMPP)

O repositório tem uma pasta `.devcontainer/` que monta automaticamente um ambiente com PHP 8.3 e MariaDB (compatível com o MySQL), com as mesmas credenciais do XAMPP. Assim o `processos.php` roda sem nenhuma alteração.

1. No GitHub, abra o repositório e clique em **Code → Codespaces → Create codespace on main**.
2. Aguarde a criação (na primeira vez leva alguns minutos). O ambiente importa o `banco.sql` sozinho.
3. Quando terminar, o navegador abre o sistema. Se não abrir, vá na aba **PORTS** e clique no ícone de globo da porta **8000**.

Observações:

- Para que outra pessoa (ex.: o professor) abra o link, clique com o botão direito na porta 8000, na aba **PORTS**, e escolha **Port Visibility → Public**.
- O Codespace desliga sozinho depois de um tempo sem uso, e o link só funciona enquanto ele estiver ligado. Ao religar, o banco e o servidor sobem de novo automaticamente, com os dados preservados.
- Para voltar o banco ao estado inicial (3 processos de exemplo), rode no terminal: `mariadb -u root --default-character-set=utf8mb4 < banco.sql`.
- O Codespaces consome horas da cota gratuita mensal enquanto está ligado. Ao terminar, pare-o em **github.com/codespaces → ··· → Stop codespace**.
