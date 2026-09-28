<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pergunta = $_POST["pergunta"];
    $resposta1 = $_POST["resposta1"];
    $resposta2 = $_POST["resposta2"];
    $resposta3 = $_POST["resposta3"];
    $correta = $_POST["correta"];

    $id = 1;

    if (file_exists("perguntas.txt")) {

        $linhas = file("perguntas.txt");

        if (count($linhas) > 1) {

            $ultimaLinha = $linhas[count($linhas) - 1];

            $dados = explode(";", $ultimaLinha);

            $id = $dados[0] + 1;
        }

    } else {

        $arq = fopen("perguntas.txt", "w");

        fwrite($arq, "id;pergunta;tipo\n");

        fclose($arq);
    }


    $arq = fopen("perguntas.txt", "a");

    $linha = $id . ";" . $pergunta . ";multipla\n";

    fwrite($arq, $linha);

    fclose($arq);


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


    if (!file_exists("respostas.txt")) {

        $arq = fopen("respostas.txt", "w");

        fwrite($arq, "id_pergunta;resposta;correta\n");

        fclose($arq);
    }


    $arq = fopen("respostas.txt", "a");

    fwrite($arq, $id . ";" . $resposta1 . ";" . $valor1 . "\n");

    fwrite($arq, $id . ";" . $resposta2 . ";" . $valor2 . "\n");

    fwrite($arq, $id . ";" . $resposta3 . ";" . $valor3 . "\n");

    fclose($arq);


    $msg = "Pergunta cadastrada com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Criar Pergunta de Múltipla Escolha</title>

</head>

<body>

<h1>Criar Pergunta de Múltipla Escolha</h1>

<form method="POST">

    <label>Pergunta:</label>

    <input type="text" name="pergunta" required>

    <br><br>

    <label>Resposta 1:</label>

    <input type="text" name="resposta1" required>

    <br><br>

    <label>Resposta 2:</label>

    <input type="text" name="resposta2" required>

    <br><br>

    <label>Resposta 3:</label>

    <input type="text" name="resposta3" required>

    <br><br>

    <label>Resposta correta:</label>

    <select name="correta" required>

        <option value="1">Resposta 1</option>
        <option value="2">Resposta 2</option>
        <option value="3">Resposta 3</option>

    </select>

    <br><br>

    <input type="submit" value="Cadastrar">

</form>

<?php

if (!empty($msg)) {

    echo "<p>$msg</p>";

}

?>

</body>

</html>