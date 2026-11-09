```php
<?php

header("Content-Type: application/json");

$dados = json_decode(file_get_contents("php://input"), true);

$numero1 = $dados["numero1"];
$numero2 = $dados["numero2"];
$operacao = $dados["operacao"];

if (!is_numeric($numero1) || !is_numeric($numero2)) {

    $resultado = "Valores inválidos!";

} else {

    $numero1 = (float) $numero1;
    $numero2 = (float) $numero2;

    if ($operacao == "soma") {

        $resultado = $numero1 + $numero2;

    } else if ($operacao == "subtracao") {

        $resultado = $numero1 - $numero2;

    } else if ($operacao == "multiplicacao") {

        $resultado = $numero1 * $numero2;

    } else if ($operacao == "divisao") {

        if ($numero2 == 0) {

            $resultado = "Não é possível dividir por zero!";

        } else {

            $resultado = $numero1 / $numero2;

        }

    } else {

        $resultado = "Operação inválida!";

    }
}

echo json_encode([
    "resultado" => $resultado
]);

?>
```
