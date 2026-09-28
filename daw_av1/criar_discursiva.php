<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pergunta = $_POST["pergunta"];

    $resposta = $_POST["resposta"];

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

    $linha = $id . ";" . $pergunta . ";discursiva\n";

    fwrite($arq, $linha);

    fclose($arq);


    if (!file_exists("respostas.txt")) {

        $arq = fopen("respostas.txt", "w");

        fwrite($arq, "id_pergunta;resposta;correta\n");

        fclose($arq);
    }


    $arq = fopen("respostas.txt", "a");

    $linha = $id . ";" . $resposta . ";1\n";

    fwrite($arq, $linha);

    fclose($arq);


    $msg = "Pergunta cadastrada com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Criar Pergunta Discursiva</title>

</head>

<body>

<h1>Criar Pergunta Discursiva</h1>

<form method="POST">

    <label>Pergunta:</label>

    <input type="text" name="pergunta" required>

    <br><br>

    <label>Resposta correta:</label>

    <input type="text" name="resposta" required>

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