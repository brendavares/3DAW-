<?php

$matricula = $_GET["matricula"];

$linhas = file("alunos.txt");

$aluno = null;

for ($i = 1; $i < count($linhas); $i++) {

    $dados = explode(";", trim($linhas[$i]));

    if ($dados[0] == $matricula) {

        $aluno = $dados;

        break;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Alterar Aluno</title>

</head>

<body>

<h1>Alterar Aluno</h1>

<?php

if ($aluno != null) {

?>

<form action="salvar_alteracao.php" method="POST">

    <input type="hidden" name="matricula" value="<?php echo $aluno[0]; ?>">

    <label>Matrícula:</label>

    <input type="text" value="<?php echo $aluno[0]; ?>" disabled>

    <br><br>

    <label>Nome:</label>

    <input type="text" name="nome" value="<?php echo $aluno[1]; ?>" required>

    <br><br>

    <label>Email:</label>

    <input type="email" name="email" value="<?php echo $aluno[2]; ?>" required>

    <br><br>

    <input type="submit" value="Salvar Alteração">

</form>

<?php

} else {

    echo "<p>Aluno não encontrado!</p>";

}

?>

</body>

</html>
