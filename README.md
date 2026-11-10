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
