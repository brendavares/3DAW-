<?php

$perguntaEncontrada = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idProcurado = $_POST["id"];

    if (file_exists("perguntas.txt")) {

        $perguntas = file("perguntas.txt");

        for ($i = 1; $i < count($perguntas); $i++) {

            $dados = explode(";", trim($perguntas[$i]));

            if ($dados[0] == $idProcurado) {

                $perguntaEncontrada = true;

                $id = $dados[0];
                $pergunta = $dados[1];
                $tipo = $dados[2];

                echo "<h2>Pergunta encontrada</h2>";
                echo "<p><b>ID:</b> $id</p>";
                echo "<p><b>Pergunta:</b> $pergunta</p>";
                echo "<p><b>Tipo:</b> $tipo</p>";

                echo "<b>Respostas:</b><br>";

                if (file_exists("respostas.txt")) {

                    $respostas = file("respostas.txt");

                    foreach ($respostas as $linha) {

                        $dadosResposta = explode(";", trim($linha));

                        if ($dadosResposta[0] == $id) {

                            echo "- " . $dadosResposta[1];

                            if ($dadosResposta[2] == "1") {
                                echo " (correta)";
                            }

                            echo "<br>";
                        }
                    }
                }

                break;
            }
        }
    }

    if (!$perguntaEncontrada) {
        echo "<p>Pergunta não encontrada.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar uma Pergunta</title>
</head>
<body>

<h1>Listar uma Pergunta</h1>

<form method="POST">

    <label>ID da pergunta:</label>
    <input type="number" name="id" required>

    <br><br>

    <input type="submit" value="Buscar">

</form>

<br>

<a href="index.php">Voltar</a>

</body>
</html>