<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro de Filiado</title>
</head>
<body>
<h1>Cadastro de Filiado</h1>
<h2>Preencha os dados abaixo</h2>
<?php if (isset($_SESSION['mensagem_erro'])): ?>
    <div class="erro">
        <p><?php echo $_SESSION['mensagem_erro']; ?></p>
    </div>
    <?php unset($_SESSION['mensagem_erro']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['mensagem_sucesso'])): ?>
    <div class="sucesso">
        <p><?php echo $_SESSION['mensagem_sucesso']; ?></p>
    </div>
    <?php unset($_SESSION['mensagem_sucesso']); ?>
<?php endif; ?>


<form method="POST" action="<?php echo Config::pegarUrl() . 'filiado/cadastrar'; ?>">
    <div>
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" placeholder="Nome" required>
    </div>

    <div>
        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" placeholder="CPF" required>
    </div>

    <div>
        <label for="rg">RG</label>
        <input type="text" id="rg" name="rg" placeholder="RG" required>
    </div>

    <div>
        <label for="dataNascimento">Data de Nascimento</label>
        <input type="date" id="dataNascimento" name="dataNascimento" placeholder="Data de Nascimento" required>
    </div>

    <div>
        <label for="idade">Idade</label>
        <input type="text" id="idade" name="idade" placeholder="Idade" required>
    </div>

    <div>
        <label for="empresa">Empresa</label>
        <input type="text" id="empresa" name="empresa" placeholder="Empresa" required>
    </div>

    <div>
        <label for="cargo">Cargo</label>
        <input type="text" id="cargo" name="cargo" placeholder="Cargo" required>
    </div>

    <div>
        <label for="situacao">Situação</label>
        <select id="situacao" name="situacao" required>
            <option value="ativo">Ativo</option>
            <option value="inativo">Inativo</option>
            <option value="aposentado">Aposentado</option>
            <option value="licenciado">Licenciado</option>
        </select>
    </div>

    <div>
        <label for="telefoneResidencial">Telefone Residencial</label>
        <input type="tel" id="telefoneResidencial" name="telefoneResidencial" placeholder="Telefone Residencial">
    </div>

    <div>
        <label for="celular">Celular</label>
        <input type="tel" id="celular" name="celular" placeholder="Celular">
    </div>

    <div>
        <button type="submit">Cadastrar</button>
    </div>
</form>

<a href="<?php echo Config::pegarUrl() . 'usuario/dashboard'; ?>">
    <button type="button">Voltar</button>
</a>
</body>
</html>
