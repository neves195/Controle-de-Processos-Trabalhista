# Sistema de Controle de Processos Trabalhistas

Sistema web para **incluir, consultar, alterar e excluir** processos trabalhistas, feito com PHP, HTML, CSS e MySQL.

Trabalho da disciplina de Programação Web (ITE, 2026).

## Funcionalidades

| Operação  | Como funciona |
|-----------|---------------|
| Incluir   | O usuário preenche Advogado, Trabalhador e Empresa e clica em **Incluir**. O número do registro é gerado pelo banco. |
| Consultar | Uma tabela abaixo do formulário lista todos os processos cadastrados. |
| Alterar   | Ao clicar em **Editar**, os dados do processo vão para o formulário e o botão muda para **Alterar**. |
| Excluir   | Ao clicar em **Excluir**, o sistema pede confirmação e remove somente o processo escolhido. |

Todas as operações mostram uma mensagem com o resultado.

## Estrutura do projeto

```
├── processos.php     # Sistema completo: conexão, PHP, formulário HTML e CSS
├── banco.sql         # Cria o banco e a tabela e insere processos de exemplo
├── README.md
└── .devcontainer/    # Configuração do GitHub Codespaces (opcional)
```

### Banco de dados

Banco `processostrabalhistas`, tabela `processos`:

| Campo         | Tipo         | Observação                     |
|---------------|--------------|--------------------------------|
| `registro`    | INT          | PRIMARY KEY, AUTO_INCREMENT    |
| `advogado`    | VARCHAR(100) | Advogado responsável           |
| `trabalhador` | VARCHAR(100) | Trabalhador (reclamante)       |
| `empresa`     | VARCHAR(100) | Empresa (reclamada)            |

O `banco.sql` recria a tabela do zero e insere 3 processos de exemplo. Ele pode ser executado quantas vezes for necessário.

## Como executar

Há duas opções: no computador, com o XAMPP, ou no navegador, com o GitHub Codespaces (sem instalar nada).

### Opção 1: XAMPP

1. **Copie o projeto** para a pasta `htdocs` do XAMPP, numa pasta chamada `processos`:
   - Windows: `C:\xampp\htdocs\processos\`
   - Linux: `/opt/lampp/htdocs/processos/`
2. **Inicie o Apache e o MySQL** no painel do XAMPP.
3. **Importe o banco**, por um dos caminhos abaixo.
4. **Abra o sistema** em <http://localhost/processos/processos.php>.

#### Importar o banco pelo phpMyAdmin

1. Acesse <http://localhost/phpmyadmin>.
2. Clique na aba **Importar**. Não é preciso selecionar um banco antes, porque o script já cria e seleciona o banco.
3. Em **Arquivo a importar**, escolha o `banco.sql` e mantenha o conjunto de caracteres **utf-8**.
4. Clique em **Importar**.

#### Importar o banco pela linha de comando

Na pasta do projeto:

```bash
# Windows
C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 < banco.sql

# Linux
/opt/lampp/bin/mysql -u root --default-character-set=utf8mb4 < banco.sql
```

Se o `mysql` estiver no PATH, basta rodar `mysql -u root --default-character-set=utf8mb4 < banco.sql`.

### Opção 2: GitHub Codespaces

A pasta `.devcontainer/` monta automaticamente um ambiente com PHP 8.3 e MariaDB (compatível com o MySQL), com as mesmas credenciais do XAMPP. O `processos.php` roda sem nenhuma alteração.

1. No GitHub, abra o repositório e clique em **Code → Codespaces → Create codespace on main**.
2. Aguarde a criação, que na primeira vez leva alguns minutos. O `banco.sql` é importado automaticamente.
3. Quando terminar, o sistema abre no navegador. Se não abrir, vá na aba **PORTS** e clique no ícone de globo da porta **8000**.

Dicas:

- **Compartilhar o link:** na aba **PORTS**, clique com o botão direito na porta 8000 e escolha **Port Visibility → Public**. Só assim outra pessoa consegue abrir.
- **Desligamento automático:** o Codespace desliga sozinho depois de um tempo sem uso, e o link só funciona enquanto ele estiver ligado. Ao religar, o banco e o servidor sobem de novo, com os dados preservados.
- **Restaurar os dados de exemplo:** rode no terminal `mariadb -u root --default-character-set=utf8mb4 < banco.sql`.
- **Cota de horas:** o Codespace consome a cota gratuita mensal enquanto está ligado. Ao terminar, pare-o em **github.com/codespaces → ··· → Stop codespace**.

## Credenciais de conexão

| Parâmetro | Valor                   |
|-----------|-------------------------|
| Host      | `localhost`             |
| Usuário   | `root`                  |
| Senha     | *(vazia)*               |
| Banco     | `processostrabalhistas` |

Essas são as credenciais padrão do XAMPP e servem apenas para desenvolvimento local.

## Roteiro de testes

1. **Consultar:** abra o sistema e confira os 3 processos de exemplo na tabela.
2. **Incluir:** preencha os três campos e clique em **Incluir**. O novo processo aparece na tabela com o próximo número de registro.
3. **Alterar:** clique em **Editar** num processo, mude algum campo e clique em **Alterar**. A tabela mostra o dado novo e o formulário volta ao modo de inclusão.
4. **Excluir:** clique em **Excluir**, confirme, e verifique que só aquele processo saiu da tabela.

Para conferir no banco, rode `SELECT * FROM processos;` no phpMyAdmin antes e depois de cada operação.

## Tecnologias

- PHP, com a extensão mysqli e comandos preparados
- MySQL / MariaDB
- HTML e CSS
