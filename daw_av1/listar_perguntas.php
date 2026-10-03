<?php

if (!file_exists("perguntas.txt")) {
    echo "Nenhuma pergunta cadastrada.";
    exit;
}

$perguntas = file("perguntas.txt");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Perguntas</title>
</head>
<body>

<h1>Perguntas e Respostas</h1>

<?php

for ($i = 1; $i < count($perguntas); $i++) {

    $dados = explode(";", trim($perguntas[$i]));

    $id = $dados[0];
    $pergunta = $dados[1];
    $tipo = $dados[2];

    echo "<h3>ID: $id</h3>";
    echo "<p><b>Pergunta:</b> $pergunta</p>";
    echo "<p><b>Tipo:</b> $tipo</p>";

    if (file_exists("respostas.txt")) {

        $respostas = file("respostas.txt");

        echo "<b>Respostas:</b><br>";

        foreach ($respostas as $linha) {

            $dadosResposta = explode(";", trim($linha));

            if ($dadosResposta[0] == $id) {

                $resposta = $dadosResposta[1];
                $correta = $dadosResposta[2];

                echo "- $resposta";

                if ($correta == "1") {
                    echo " (correta)";
                }

                echo "<br>";
            }
        }
    }

    echo "<hr>";
}

?>

<a href="index.php">Voltar</a>

</body>
</html>