<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST["nome"];
    $matricula = $_POST["matricula"];
    $curso = $_POST["curso"];

    $msg = "";

    if (!file_exists("alunos.txt")) {

        $arqAluno = fopen("alunos.txt", "w") or die("Erro ao criar arquivo");

        fwrite($arqAluno, "nome;matricula;curso\n");

        fclose($arqAluno);
    }

    $arqAluno = fopen("alunos.txt", "a") or die("Erro ao abrir arquivo");

    $linha = $nome . ";" . $matricula . ";" . $curso . "\n";

    fwrite($arqAluno, $linha);

    fclose($arqAluno);

    $msg = "Aluno cadastrado com sucesso!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Aluno</title>

</head>

<body>

    <h1>Cadastro de Aluno</h1>

    <form action="incluir_aluno.php" method="POST">

        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <br><br>

        <label for="matricula">Matrícula:</label>
        <input type="text" name="matricula" id="matricula" required>

        <br><br>

        <label for="curso">Curso:</label>
        <input type="text" name="curso" id="curso" required>

        <br><br>

        <input type="submit" value="Cadastrar Aluno">

    </form>

    <?php if (!empty($msg)) { ?>

        <p><?php echo $msg; ?></p>

    <?php } ?>

</body>

</html>
