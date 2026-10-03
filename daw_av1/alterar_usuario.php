<?php

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];

    $usuarios = file("usuarios.txt");
    $novosUsuarios = [];

    for ($i = 0; $i < count($usuarios); $i++) {

        if ($i == 0) {
            $novosUsuarios[] = $usuarios[$i];
            continue;
        }

        $dados = explode(";", trim($usuarios[$i]));

        if ($dados[0] == $id) {
            $novosUsuarios[] = $id . ";" . $nome . ";" . $email . "\n";
        } else {
            $novosUsuarios[] = $usuarios[$i];
        }
    }

    file_put_contents("usuarios.txt", $novosUsuarios);

    $msg = "Usuário alterado com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Usuário</title>
</head>
<body>

<h1>Alterar Usuário</h1>

<form method="POST">

    <label>ID do usuário:</label>
    <input type="number" name="id" required>

    <br><br>

    <label>Novo nome:</label>
    <input type="text" name="nome" required>

    <br><br>

    <label>Novo e-mail:</label>
    <input type="email" name="email" required>

    <br><br>

    <input type="submit" value="Alterar">

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