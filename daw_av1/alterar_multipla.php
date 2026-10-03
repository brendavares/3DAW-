<?php

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST["id"];
    $pergunta = $_POST["pergunta"];
    $resposta1 = $_POST["resposta1"];
    $resposta2 = $_POST["resposta2"];
    $resposta3 = $_POST["resposta3"];
    $correta = $_POST["correta"];

    $perguntas = file("perguntas.txt");
    $novasPerguntas = [];

    for ($i = 0; $i < count($perguntas); $i++) {

        if ($i == 0) {
            $novasPerguntas[] = $perguntas[$i];
            continue;
        }

        $dados = explode(";", trim($perguntas[$i]));

        if ($dados[0] == $id) {
            $novasPerguntas[] = $id . ";" . $pergunta . ";multipla\n";
        } else {
            $novasPerguntas[] = $perguntas[$i];
        }
    }

    file_put_contents("perguntas.txt", $novasPerguntas);

    if ($correta == "1") {
        $valor1 = 1;
        $valor2 = 0;
        $valor3 = 0;
    } else if ($correta == "2") {
        $valor1 = 0;
        $valor2 = 1;
        $valor3 = 0;
    } else {
        $valor1 = 0;
        $valor2 = 0;
        $valor3 = 1;
    }

    $respostas = file("respostas.txt");
    $novasRespostas = [];

    foreach ($respostas as $linha) {

        $dados = explode(";", trim($linha));

        if ($dados[0] == $id) {
            continue;
        }

        $novasRespostas[] = $linha;
    }

    $novasRespostas[] = $id . ";" . $resposta1 . ";" . $valor1 . "\n";
    $novasRespostas[] = $id . ";" . $resposta2 . ";" . $valor2 . "\n";
    $novasRespostas[] = $id . ";" . $resposta3 . ";" . $valor3 . "\n";

    file_put_contents("respostas.txt", $novasRespostas);

    $msg = "Pergunta alterada com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Múltipla Escolha</title>
</head>
<body>

<h1>Alterar Pergunta de Múltipla Escolha</h1>

<form method="POST">

    <label>ID da pergunta:</label>
    <input type="number" name="id" required>

    <br><br>

    <label>Nova pergunta:</label>
    <input type="text" name="pergunta" required>

    <br><br>

    <label>Nova resposta 1:</label>
    <input type="text" name="resposta1" required>

    <br><br>

    <label>Nova resposta 2:</label>
    <input type="text" name="resposta2" required>

    <br><br>

    <label>Nova resposta 3:</label>
    <input type="text" name="resposta3" required>

    <br><br>

    <label>Nova resposta correta:</label>

    <select name="correta" required>
        <option value="1">Resposta 1</option>
        <option value="2">Resposta 2</option>
        <option value="3">Resposta 3</option>
    </select>

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