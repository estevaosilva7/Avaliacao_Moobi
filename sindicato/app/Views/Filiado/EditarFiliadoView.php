<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Filiado</title>
</head>
<body>
<header>
    <h1>Atualizar Dados do Filiado</h1>
</header>
<?php
if ($mensagemErro = CustomSessionHandler::get('mensagem_erro')) {
    echo "<p>$mensagemErro</p>";
    CustomSessionHandler::remove('mensagem_erro');
}

if ($mensagemSucesso = CustomSessionHandler::get('mensagem_sucesso')) {
    echo "<p>$mensagemSucesso</p>";
    CustomSessionHandler::remove('mensagem_sucesso');
}
?>
<form action="<?php echo Config::pegarUrl() . 'filiado/atualizar?id=' . $mFiliado['flo_Id']; ?>" method="POST">

    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($mFiliado['flo_Nome']); ?>" required readonly>
    <br>

    <label for="cpf">CPF:</label>
    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($mFiliado['flo_CPF']); ?>" required readonly>
    <br>

    <label for="rg">RG:</label>
    <input type="text" id="rg" name="rg" value="<?php echo htmlspecialchars($mFiliado['flo_RG']); ?>" required readonly>
    <br>

    <label for="dataNascimento">Data de Nascimento:</label>
    <input type="date" id="dataNascimento" name="dataNascimento" value="<?php echo htmlspecialchars($mFiliado['flo_Data_De_Nascimento']); ?>" required readonly>
    <br>

    <label for="idade">Idade:</label>
    <input type="text" id="idade" name="idade" value="<?php echo htmlspecialchars($mFiliado['flo_Idade']); ?>" required readonly>
    <br>

    <label for="empresa">Empresa:</label>
    <input type="text" id="empresa" name="empresa" value="<?php echo htmlspecialchars($mFiliado['flo_Empresa']); ?>" required>
    <br>

    <label for="cargo">Cargo:</label>
    <input type="text" id="cargo" name="cargo" value="<?php echo htmlspecialchars($mFiliado['flo_Cargo']); ?>" required>
    <br>

    <label for="situacao">Situação:</label>
    <select id="situacao" name="situacao" required>
        <option value="ativo" <?php echo $mFiliado['flo_Situacao'] == 'ativo' ? 'selected' : ''; ?>>Ativo</option>
        <option value="aposentado" <?php echo $mFiliado['flo_Situacao'] == 'aposentado' ? 'selected' : ''; ?>>Aposentado</option>
        <option value="licenciado" <?php echo $mFiliado['flo_Situacao'] == 'licenciado' ? 'selected' : ''; ?>>Licenciado</option>
    </select>
    <br>

    <label for="telefoneResidencial">Telefone Residencial:</label>
    <input type="text" id="telefoneResidencial" name="telefoneResidencial" value="<?php echo htmlspecialchars($mFiliado['flo_Telefone_Residencial']); ?>" placeholder="Telefone Residencial" readonly>
    <br>

    <label for="celular">Celular:</label>
    <input type="text" id="celular" name="celular" value="<?php echo htmlspecialchars($mFiliado['flo_Celular']); ?>" placeholder="Celular" readonly>
    <br>

    <button type="submit">Atualizar</button>
</form>
<a href="<?php echo Config::pegarUrl() . 'filiado/listar'; ?>">
    <button type="button">Voltar</button>
</body>
</html>
