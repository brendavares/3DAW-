<?php

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST["id"];
    $pergunta = $_POST["pergunta"];
    $resposta = $_POST["resposta"];

    $perguntas = file("perguntas.txt");
    $novasPerguntas = [];

    for ($i = 0; $i < count($perguntas); $i++) {

        if ($i == 0) {
            $novasPerguntas[] = $perguntas[$i];
            continue;
        }

        $dados = explode(";", trim($perguntas[$i]));

        if ($dados[0] == $id) {
            $novasPerguntas[] = $id . ";" . $pergunta . ";discursiva\n";
        } else {
            $novasPerguntas[] = $perguntas[$i];
        }
    }

    file_put_contents("perguntas.txt", $novasPerguntas);

    $respostas = file("respostas.txt");
    $novasRespostas = [];

    foreach ($respostas as $linha) {

        $dados = explode(";", trim($linha));

        if ($dados[0] == $id) {
            continue;
        }

        $novasRespostas[] = $linha;
    }

    $novasRespostas[] = $id . ";" . $resposta . ";1\n";

    file_put_contents("respostas.txt", $novasRespostas);

    $msg = "Pergunta alterada com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Pergunta de Texto</title>
</head>
<body>

<h1>Alterar Pergunta de Texto</h1>

<form method="POST">

    <label>ID da pergunta:</label>
    <input type="number" name="id" required>

    <br><br>

    <label>Nova pergunta:</label>
    <input type="text" name="pergunta" required>

    <br><br>

    <label>Nova resposta:</label>
    <input type="text" name="resposta" required>

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