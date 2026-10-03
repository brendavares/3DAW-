<?php

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST["id"];

    if (file_exists("perguntas.txt")) {

        $perguntas = file("perguntas.txt");
        $novasPerguntas = [];

        foreach ($perguntas as $indice => $linha) {

            if ($indice == 0) {
                $novasPerguntas[] = $linha;
                continue;
            }

            $dados = explode(";", trim($linha));

            if ($dados[0] != $id) {
                $novasPerguntas[] = $linha;
            }
        }

        file_put_contents("perguntas.txt", $novasPerguntas);
    }

    if (file_exists("respostas.txt")) {

        $respostas = file("respostas.txt");
        $novasRespostas = [];

        foreach ($respostas as $linha) {

            $dados = explode(";", trim($linha));

            if ($dados[0] != $id) {
                $novasRespostas[] = $linha;
            }
        }

        file_put_contents("respostas.txt", $novasRespostas);
    }

    $msg = "Pergunta e respostas excluídas com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Pergunta</title>
</head>
<body>

<h1>Excluir Pergunta</h1>

<form method="POST">

    <label>ID da pergunta:</label>
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