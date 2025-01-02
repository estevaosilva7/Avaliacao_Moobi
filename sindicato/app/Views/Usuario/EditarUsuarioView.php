<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Usuário</title>
</head>
<body>
<header>
    <h1>Editar Usuário</h1>
</header>
<?php
if ($mMensagemErro = CustomSessionHandler::get('mensagem_erro')) {
    echo "<p>$mMensagemErro</p>";
    CustomSessionHandler::remove('mensagem_erro');
}
if ($mMensagemSucesso = CustomSessionHandler::get('mensagem_sucesso')) {
    echo "<p>$mMensagemSucesso</p>";
    CustomSessionHandler::remove('mensagem_sucesso');
}
?>
<?php if ($aUsuario): ?>
    <form action="<?php echo Config::pegarUrl() . 'usuario/atualizar?id=' . $aUsuario['usu_Id']; ?>" method="POST">
        <label for="nome">Nome de Usuário:</label>
        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($aUsuario['usu_Nome']); ?>" required>
        <br><br>
        <label for="tipo">Tipo de Usuário:</label>
        <select name="tipo" id="tipo">
            <option value="admin" <?php echo $aUsuario['usu_Tipo'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="comum" <?php echo $aUsuario['usu_Tipo'] == 'comum' ? 'selected' : ''; ?>>Comum</option>
        </select>
        <br><br>
        <button type="submit">Atualizar Usuário</button>
    </form>
<a href="<?php echo Config::pegarUrl() . 'usuario/listar'; ?>">
    <button type="button">Voltar</button>
<?php else: ?>
    <p>Usuário não encontrado.</p>
<?php endif; ?>
</body>
</html>