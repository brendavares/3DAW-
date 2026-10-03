<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $matricula = $_POST["matricula"];
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $endereco = $_POST["endereco"];

    if (file_exists("professores.txt")) {

        $linhas = file("professores.txt");

        $novoArquivo = "";

        $encontrou = false;

        foreach ($linhas as $posicao => $linha) {

            if ($posicao == 0) {

                $novoArquivo .= $linha;

            } else {

                $dados = explode(";", trim($linha));

                if ($dados[0] == $matricula) {

                    $novoArquivo .= $matricula . ";" . $nome . ";" . $cpf . ";" . $endereco . "\n";

                    $encontrou = true;

                } else {

                    $novoArquivo .= $linha;
                }
            }
        }


        if ($encontrou) {

            $arq = fopen("professores.txt", "w");

            fwrite($arq, $novoArquivo);

            fclose($arq);

            $msg = "Professor alterado com sucesso!";

        } else {

            $msg = "Professor não encontrado!";
        }

    } else {

        $msg = "Arquivo professores.txt não existe!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Alterar Professor</title>

</head>

<body>

<h1>Alterar Professor</h1>

<form method="POST">

    <label>Matrícula:</label>

    <input type="text" name="matricula" required>

    <br><br>

    <label>Nome:</label>

    <input type="text" name="nome" required>

    <br><br>

    <label>CPF:</label>

    <input type="text" name="cpf" required>

    <br><br>

    <label>Endereço:</label>

    <input type="text" name="endereco" required>

    <br><br>

    <input type="submit" value="Alterar">

</form>

<?php

if (!empty($msg)) {

    echo "<p>$msg</p>";

}

?>

</body>

</html>
