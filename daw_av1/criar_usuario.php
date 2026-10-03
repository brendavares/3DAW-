<?php

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST["nome"];
    $email = $_POST["email"];

    $id = 1;

    if (file_exists("usuarios.txt")) {

        $linhas = file("usuarios.txt");

        if (count($linhas) > 1) {

            $ultimaLinha = $linhas[count($linhas) - 1];

            $dados = explode(";", trim($ultimaLinha));

            $id = $dados[0] + 1;
        }

    } else {

        $arq = fopen("usuarios.txt", "w");

        fwrite($arq, "id;nome;email\n");

        fclose($arq);
    }

    $arq = fopen("usuarios.txt", "a");

    fwrite($arq, $id . ";" . $nome . ";" . $email . "\n");

    fclose($arq);

    $msg = "Usuário cadastrado com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Usuário</title>
</head>
<body>

<h1>Criar Usuário</h1>

<form method="POST">

    <label>Nome:</label>
    <input type="text" name="nome" required>

    <br><br>

    <label>E-mail:</label>
    <input type="email" name="email" required>

    <br><br>

    <input type="submit" value="Cadastrar">

</form>

<?php

if (!empty($msg)) {
    echo "<p>$msg</p>";
}

?>

<br>
<a href="index.php">Voltar</a>

</body>
</html>