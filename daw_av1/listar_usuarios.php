<?php

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Usuários</title>
</head>
<body>

<h1>Usuários</h1>

<?php

if (!file_exists("usuarios.txt")) {

    echo "<p>Nenhum usuário cadastrado.</p>";

} else {

    $usuarios = file("usuarios.txt");

    for ($i = 1; $i < count($usuarios); $i++) {

        $dados = explode(";", trim($usuarios[$i]));

        echo "<p>";
        echo "<b>ID:</b> " . $dados[0] . "<br>";
        echo "<b>Nome:</b> " . $dados[1] . "<br>";
        echo "<b>E-mail:</b> " . $dados[2];
        echo "</p>";

        echo "<hr>";
    }
}

?>

<a href="index.php">Voltar</a>

</body>
</html>