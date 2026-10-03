<?php

$matricula = $_POST["matricula"];

$linhas = file("alunos.txt");

$novoArquivo = "";

$novoArquivo .= $linhas[0];

for ($i = 1; $i < count($linhas); $i++) {

    $dados = explode(";", trim($linhas[$i]));

    if ($dados[0] != $matricula) {

        $novoArquivo .= $linhas[$i];

    }
}

$arq = fopen("alunos.txt", "w");

fwrite($arq, $novoArquivo);

fclose($arq);

echo "<h2>Aluno excluído com sucesso!</h2>";

echo "<a href='listar_alunos.php'>Voltar para lista</a>";

?>
