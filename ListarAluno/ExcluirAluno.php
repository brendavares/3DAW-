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

    <title>Excluir Aluno</title>

</head>

<body>

<h1>Excluir Aluno</h1>

<?php

if ($aluno != null) {

?>

<p><strong>Matrícula:</strong> <?php echo $aluno[0]; ?></p>

<p><strong>Nome:</strong> <?php echo $aluno[1]; ?></p>

<p><strong>Email:</strong> <?php echo $aluno[2]; ?></p>

<p>Deseja realmente excluir este aluno?</p>

<form action="confirmar_exclusao.php" method="POST">

    <input type="hidden" name="matricula" value="<?php echo $aluno[0]; ?>">

    <input type="submit" value="Confirmar Exclusão">

</form>

<br>

<a href="listar_alunos.php">

    <button>Cancelar</button>

</a>

<?php

} else {

    echo "<p>Aluno não encontrado!</p>";

}

?>

</body>

</html>
