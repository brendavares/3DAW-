<?php

$linhas = file("alunos.txt");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Lista de Alunos</title>

</head>

<body>

<h1>Lista de Alunos</h1>

<table border="1">

    <tr>

        <th>Matrícula</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Ações</th>

    </tr>

<?php

for ($i = 1; $i < count($linhas); $i++) {

    $dados = explode(";", trim($linhas[$i]));

?>

    <tr>

        <td><?php echo $dados[0]; ?></td>

        <td><?php echo $dados[1]; ?></td>

        <td><?php echo $dados[2]; ?></td>

        <td>

            <a href="alterar_aluno.php?matricula=<?php echo $dados[0]; ?>">
                <button>Alterar</button>
            </a>

            <a href="excluir_aluno.php?matricula=<?php echo $dados[0]; ?>">
                <button>Excluir</button>
            </a>

        </td>

    </tr>

<?php

}

?>

</table>

</body>

</html>
