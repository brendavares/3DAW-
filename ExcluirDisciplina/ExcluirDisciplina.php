<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $disciplina = $_POST["disciplina"];

    if (file_exists("disciplinas.txt")) {

        $arqDisciplina = fopen("disciplinas.txt", "r");

        $disciplinas = [];

        while (!feof($arqDisciplina)) {

            $linha = fgets($arqDisciplina);

            if ($linha != "" && trim($linha) != $disciplina) {
                $disciplinas[] = $linha;
            }
        }

        fclose($arqDisciplina);

        $arqDisciplina = fopen("disciplinas.txt", "w");

        foreach ($disciplinas as $linha) {
            fwrite($arqDisciplina, $linha);
        }

        fclose($arqDisciplina);

        $msg = "Disciplina excluída com sucesso!";

    } else {

        $msg = "Arquivo de disciplinas não encontrado.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Excluir Disciplina</title>

</head>

<body>

    <h1>Excluir Disciplina</h1>

    <form method="POST">

        <label for="disciplina">Nome da disciplina:</label>

        <input type="text" name="disciplina" id="disciplina" required>

        <br><br>

        <input type="submit" value="Excluir Disciplina">

    </form>

    <?php if (!empty($msg)) { ?>

        <p><?php echo $msg; ?></p>

    <?php } ?>

</body>

</html>
