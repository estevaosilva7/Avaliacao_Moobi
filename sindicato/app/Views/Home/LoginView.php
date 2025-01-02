<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<form method="POST" action="<?php echo Config::pegarUrl() . 'usuario/login'; ?>">
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" placeholder="Nome" required>

    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha" placeholder="Senha" required>

    <button type="submit">Login</button>
</form>
</body>
</html>
