<?php

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST["id"];

    if (file_exists("usuarios.txt")) {

        $usuarios = file("usuarios.txt");
        $novosUsuarios = [];

        foreach ($usuarios as $indice => $linha) {

            if ($indice == 0) {
                $novosUsuarios[] = $linha;
                continue;
            }

            $dados = explode(";", trim($linha));

            if ($dados[0] != $id) {
                $novosUsuarios[] = $linha;
            }
        }

        file_put_contents("usuarios.txt", $novosUsuarios);
    }

    $msg = "Usuário excluído com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Usuário</title>
</head>
<body>

<h1>Excluir Usuário</h1>

<form method="POST">

    <label>ID do usuário:</label>
    <input type="number" name="id" required>

    <br><br>

    <input type="submit" value="Excluir">

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