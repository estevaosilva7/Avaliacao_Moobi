<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Dependente</title>
</head>
<body>
<h1>Editar Dependente</h1>
<form action="<?php echo Config::pegarUrl() . 'dependente/editar&id=' . $aDependente['dpe_Id']; ?>" method="POST">

    <input type="hidden" name="id" value="<?php echo $aDependente['dpe_Id']; ?>">
    <input type="hidden" name="filiadoId" value="<?php echo $aDependente['flo_Id']; ?>">

    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($aDependente['dpe_Nome']); ?>" required>
    <br>

    <label for="dataNascimento">Data de Nascimento:</label>
    <input type="date" id="dataNascimento" name="dataNascimento" value="<?php echo htmlspecialchars($aDependente['dpe_Data_De_Nascimento']); ?>" required>
    <br>

    <label for="grauParentesco">Grau de Parentesco:</label>
    <input type="text" id="grauParentesco" name="grauParentesco" value="<?php echo htmlspecialchars($aDependente['dpe_Grau_De_Parentesco']); ?>" required>
    <br>

    <button name="atualizar" type="submit">Atualizar</button>
</form>

<a href="<?php echo Config::pegarUrl() . 'dependente/listar&id=' . $aDependente['flo_Id']?>">
    <button type="button">Voltar</button>
</a>
</body>
</html>
