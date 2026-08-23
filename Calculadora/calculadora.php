<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $num1 = $_POST["num1"];
    $num2 = $_POST["num2"];
    $operacao = $_POST["operacao"];

    if ($operacao == "soma") {
        $resultado = $num1 + $num2;
    } elseif ($operacao == "subtracao") {
        $resultado = $num1 - $num2;
    } elseif ($operacao == "multiplicacao") {
        $resultado = $num1 * $num2;
    } elseif ($operacao == "divisao") {

        if ($num2 != 0) {
            $resultado = $num1 / $num2;
        } else {
            $resultado = "Não é possível dividir por zero.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Calculadora</title>

</head>

<body>

    <h1>Calculadora</h1>

    <form method="POST">

        <label for="num1">Primeiro número:</label>
        <input type="number" name="num1" id="num1" step="any" required>

        <br><br>

        <label for="num2">Segundo número:</label>
        <input type="number" name="num2" id="num2" step="any" required>

        <br><br>

        <label for="operacao">Operação:</label>

        <select name="operacao" id="operacao">

            <option value="soma">Soma (+)</option>
            <option value="subtracao">Subtração (-)</option>
            <option value="multiplicacao">Multiplicação (*)</option>
            <option value="divisao">Divisão (/)</option>

        </select>

        <br><br>

        <input type="submit" value="Calcular">

    </form>

    <?php if (isset($resultado)) { ?>

        <h2>Resultado: <?php echo $resultado; ?></h2>

    <?php } ?>

</body>

</html>
