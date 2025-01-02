<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Painel de Controle</title>
</head>
<body>
<header>
    <h1>Bem-vindo ao Painel de Controle</h1>
</header>
    <ul>
        <li><a href="<?php echo Config::pegarUrl() . 'usuario/cadastrar'; ?>">Cadastrar Usuário</a></li>
        <li><a href=<?php echo Config::pegarUrl() . 'usuario/listar'; ?>>Listar Usuários</a></li>

        <li><a href="<?php echo Config::pegarUrl() . 'filiado/cadastrarFiliado'; ?>">Cadastrar Filiado</a></li>
        <li><a href=<?php echo Config::pegarUrl() . 'filiado/listar'; ?>>Listar Filiado</a></li>

        <li><a href="<?php echo Config::pegarUrl() . 'usuario/logout'; ?>">Sair</a></li>
    </ul>
</body>
</html>