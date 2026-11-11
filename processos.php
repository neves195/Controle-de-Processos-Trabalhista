<?php
// ============================================================
// Sistema de Controle de Processos Trabalhistas
// Arquivo único: inclusão, consulta, alteração e exclusão
// ============================================================

// Faz as funções mysqli retornarem false em caso de erro (em vez de
// lançar exceção), para que as verificações com if funcionem.
mysqli_report(MYSQLI_REPORT_OFF);

// ------------------------------------------------------------
// 2. Conexão com o MySQL, com verificação de erro
// ------------------------------------------------------------
$conexao = mysqli_connect("localhost", "root", "", "processostrabalhistas");

if (!$conexao) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");

// Variável de controle: indica a operação do formulário ("incluir" ou "alterar")
$operacao = "incluir";

// Mensagem com o resultado das operações e seu tipo ("sucesso" ou "erro")
$mensagem     = "";
$tipoMensagem = "";

// Valores exibidos no formulário
$registro    = "";
$advogado    = "";
$trabalhador = "";
$empresa     = "";

// ------------------------------------------------------------
// Tratamento das operações enviadas por POST
// ------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["operacao"];

    // --------------------------------------------------------
    // 3. Inclusão
    // --------------------------------------------------------
    if ($acao == "incluir") {
        $advogado    = trim($_POST["advogado"]);
        $trabalhador = trim($_POST["trabalhador"]);
        $empresa     = trim($_POST["empresa"]);

        if ($advogado == "" || $trabalhador == "" || $empresa == "") {
            $mensagem     = "Preencha os três campos.";
            $tipoMensagem = "erro";
        } else {
            $sql  = "INSERT INTO processos (advogado, trabalhador, empresa) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $advogado, $trabalhador, $empresa);

            if (mysqli_stmt_execute($stmt)) {
                $mensagem     = "Processo nº " . mysqli_insert_id($conexao) . " incluído com sucesso!";
                $tipoMensagem = "sucesso";
                $advogado     = "";
                $trabalhador  = "";
                $empresa      = "";
            } else {
                $mensagem     = "Erro ao incluir processo: " . mysqli_stmt_error($stmt);
                $tipoMensagem = "erro";
            }

            mysqli_stmt_close($stmt);
        }
    }

    // --------------------------------------------------------
    // 5. Alteração
    // --------------------------------------------------------
    if ($acao == "alterar") {
        $registro    = $_POST["registro"];
        $advogado    = trim($_POST["advogado"]);
        $trabalhador = trim($_POST["trabalhador"]);
        $empresa     = trim($_POST["empresa"]);

        if ($advogado == "" || $trabalhador == "" || $empresa == "") {
            $mensagem     = "Preencha os três campos.";
            $tipoMensagem = "erro";
            $operacao     = "alterar"; // continua no modo de alteração
        } else {
            $sql  = "UPDATE processos SET advogado = ?, trabalhador = ?, empresa = ? WHERE registro = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $advogado, $trabalhador, $empresa, $registro);

            if (mysqli_stmt_execute($stmt)) {
                $mensagem     = "Processo nº $registro alterado com sucesso!";
                $tipoMensagem = "sucesso";
            } else {
                $mensagem     = "Erro ao alterar processo: " . mysqli_stmt_error($stmt);
                $tipoMensagem = "erro";
            }

            mysqli_stmt_close($stmt);

            // Retorna o formulário ao modo de inclusão
            $operacao    = "incluir";
            $registro    = "";
            $advogado    = "";
            $trabalhador = "";
            $empresa     = "";
        }
    }

    // --------------------------------------------------------
    // 6. Exclusão (POST com comando preparado)
    // --------------------------------------------------------
    if ($acao == "excluir") {
        $registroExcluir = $_POST["registro"];

        $sql  = "DELETE FROM processos WHERE registro = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "i", $registroExcluir);

        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
            $mensagem     = "Processo nº $registroExcluir excluído com sucesso!";
            $tipoMensagem = "sucesso";
        } else {
            $mensagem     = "Erro ao excluir: processo não encontrado.";
            $tipoMensagem = "erro";
        }

        mysqli_stmt_close($stmt);
    }
}

// ------------------------------------------------------------
// 5. Edição: recebe o registro pela URL ($_GET) e preenche o formulário
// ------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["editar"])) {
    $registroEditar = $_GET["editar"];

    $sql  = "SELECT * FROM processos WHERE registro = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $registroEditar);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($linha = mysqli_fetch_assoc($resultado)) {
        $operacao    = "alterar";
        $registro    = $linha["registro"];
        $advogado    = $linha["advogado"];
        $trabalhador = $linha["trabalhador"];
        $empresa     = $linha["empresa"];
    } else {
        $mensagem     = "Processo não encontrado.";
        $tipoMensagem = "erro";
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Processos Trabalhistas</title>

    <!-- 7. Formatação CSS -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 16px;
            font-family: "Segoe UI", Arial, sans-serif;
            background-color: #eef1f5;
            color: #2c3e50;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            margin: 0 0 4px;
            color: #1f3a5f;
        }

        .subtitulo {
            margin: 0 0 24px;
            color: #6b7c93;
        }

        h2 {
            margin-top: 0;
            font-size: 1.2em;
            color: #1f3a5f;
        }

        .cartao {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .mensagem {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-weight: bold;
        }

        .mensagem.sucesso {
            background-color: #e3f6e8;
            color: #1e7b3a;
            border-left: 5px solid #1e7b3a;
        }

        .mensagem.erro {
            background-color: #fde8e8;
            color: #b42318;
            border-left: 5px solid #b42318;
        }

        .campos {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            font-size: 0.9em;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #c5ced9;
            border-radius: 6px;
            font-size: 1em;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #2f6fb3;
            box-shadow: 0 0 0 3px rgba(47, 111, 179, 0.2);
        }

        .botoes {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.95em;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-incluir {
            background-color: #2f6fb3;
            color: #ffffff;
        }

        .btn-incluir:hover {
            background-color: #245a93;
        }

        .btn-alterar {
            background-color: #e0a100;
            color: #ffffff;
        }

        .btn-alterar:hover {
            background-color: #b88400;
        }

        .btn-cancelar {
            background-color: #e4e8ee;
            color: #2c3e50;
        }

        .modo-edicao {
            color: #b88400;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e4e8ee;
        }

        th {
            background-color: #1f3a5f;
            color: #ffffff;
            font-size: 0.9em;
        }

        tr:nth-child(even) td {
            background-color: #f7f9fb;
        }

        tr:hover td {
            background-color: #eaf2fb;
        }

        .acoes {
            white-space: nowrap;
        }

        .acoes form {
            display: inline;
        }

        .btn-pequeno {
            padding: 6px 12px;
            font-size: 0.85em;
        }

        .btn-editar {
            background-color: #2f6fb3;
            color: #ffffff;
        }

        .btn-excluir {
            background-color: #d64545;
            color: #ffffff;
        }

        .btn-excluir:hover {
            background-color: #b42318;
        }

        .vazio {
            text-align: center;
            color: #6b7c93;
        }

        @media (max-width: 700px) {
            .campos {
                grid-template-columns: 1fr;
            }

            .tabela-rolagem {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
<div class="container">

    <h1>Controle de Processos Trabalhistas</h1>
    <p class="subtitulo">Inclusão, consulta, alteração e exclusão de processos</p>

    <?php if ($mensagem != "") { ?>
        <div class="mensagem <?php echo $tipoMensagem; ?>">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php } ?>

    <!-- 2. Formulário único para inclusão e alteração -->
    <div class="cartao">
        <?php if ($operacao == "alterar") { ?>
            <h2>Alterar processo <span class="modo-edicao">nº <?php echo htmlspecialchars($registro); ?></span></h2>
        <?php } else { ?>
            <h2>Novo processo</h2>
        <?php } ?>

        <form method="post" action="processos.php">
            <!-- Campo oculto com o registro do processo -->
            <input type="hidden" name="registro" value="<?php echo htmlspecialchars($registro); ?>">
            <!-- Campo oculto com a variável de controle da operação -->
            <input type="hidden" name="operacao" value="<?php echo $operacao; ?>">

            <div class="campos">
                <div>
                    <label for="advogado">Advogado</label>
                    <input type="text" id="advogado" name="advogado" maxlength="100" required
                           value="<?php echo htmlspecialchars($advogado); ?>">
                </div>
                <div>
                    <label for="trabalhador">Trabalhador</label>
                    <input type="text" id="trabalhador" name="trabalhador" maxlength="100" required
                           value="<?php echo htmlspecialchars($trabalhador); ?>">
                </div>
                <div>
                    <label for="empresa">Empresa</label>
                    <input type="text" id="empresa" name="empresa" maxlength="100" required
                           value="<?php echo htmlspecialchars($empresa); ?>">
                </div>
            </div>

            <div class="botoes">
                <?php if ($operacao == "alterar") { ?>
                    <button type="submit" class="btn btn-alterar">Alterar</button>
                    <a href="processos.php" class="btn btn-cancelar">Cancelar</a>
                <?php } else { ?>
                    <button type="submit" class="btn btn-incluir">Incluir</button>
                <?php } ?>
            </div>
        </form>
    </div>

    <!-- 4. Consulta dos processos -->
    <div class="cartao">
        <h2>Processos cadastrados</h2>

        <div class="tabela-rolagem">
            <table>
                <tr>
                    <th>Registro</th>
                    <th>Advogado</th>
                    <th>Trabalhador</th>
                    <th>Empresa</th>
                    <th>Ações</th>
                </tr>

                <?php
                $sql       = "SELECT * FROM processos";
                $resultado = mysqli_query($conexao, $sql);
                $total     = 0;

                while ($linha = mysqli_fetch_assoc($resultado)) {
                    $total++;
                ?>
                    <tr>
                        <td><?php echo $linha["registro"]; ?></td>
                        <td><?php echo htmlspecialchars($linha["advogado"]); ?></td>
                        <td><?php echo htmlspecialchars($linha["trabalhador"]); ?></td>
                        <td><?php echo htmlspecialchars($linha["empresa"]); ?></td>
                        <td class="acoes">
                            <!-- Editar: envia o registro pela URL ($_GET) -->
                            <a href="processos.php?editar=<?php echo $linha["registro"]; ?>"
                               class="btn btn-pequeno btn-editar">Editar</a>

                            <!-- Excluir: envia o registro por POST, após confirmação -->
                            <form method="post" action="processos.php"
                                  onsubmit="return confirm('Deseja realmente excluir o processo nº <?php echo $linha["registro"]; ?>?');">
                                <input type="hidden" name="operacao" value="excluir">
                                <input type="hidden" name="registro" value="<?php echo $linha["registro"]; ?>">
                                <button type="submit" class="btn btn-pequeno btn-excluir">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php
                }

                if ($total == 0) {
                ?>
                    <tr>
                        <td colspan="5" class="vazio">Nenhum processo cadastrado.</td>
                    </tr>
                <?php
                }

                mysqli_close($conexao);
                ?>
            </table>
        </div>
    </div>

</div>
</body>
</html>
