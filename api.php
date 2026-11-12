<?php
header("Content-Type: application/json; charset=UTF-8");

function responder($sucesso, $dados = null, $mensagem = "") {
    echo json_encode([
        "sucesso" => $sucesso,
        "dados" => $dados,
        "mensagem" => $mensagem
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function limpar($valor) {
    return str_replace([";", "\r", "\n"], [",", " ", " "], trim((string)$valor));
}

function lerPerguntas() {
    if (!file_exists("perguntas.txt")) {
        file_put_contents("perguntas.txt", "id;pergunta;tipo\n");
    }
    $linhas = file("perguntas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $resultado = [];
    for ($i = 1; $i < count($linhas); $i++) {
        $d = explode(";", $linhas[$i], 3);
        if (count($d) == 3) {
            $resultado[] = ["id" => $d[0], "pergunta" => $d[1], "tipo" => $d[2]];
        }
    }
    return $resultado;
}

function lerRespostas() {
    if (!file_exists("respostas.txt")) {
        file_put_contents("respostas.txt", "id_pergunta;resposta;correta\n");
    }
    $linhas = file("respostas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $resultado = [];
    for ($i = 1; $i < count($linhas); $i++) {
        $d = explode(";", $linhas[$i], 3);
        if (count($d) == 3) {
            $resultado[] = ["id" => $d[0], "resposta" => $d[1], "correta" => $d[2]];
        }
    }
    return $resultado;
}

function salvarPerguntas($lista) {
    $conteudo = "id;pergunta;tipo\n";
    foreach ($lista as $p) {
        $conteudo .= $p["id"] . ";" . limpar($p["pergunta"]) . ";" . $p["tipo"] . "\n";
    }
    file_put_contents("perguntas.txt", $conteudo, LOCK_EX);
}

function salvarRespostas($lista) {
    $conteudo = "id_pergunta;resposta;correta\n";
    foreach ($lista as $r) {
        $conteudo .= $r["id"] . ";" . limpar($r["resposta"]) . ";" . $r["correta"] . "\n";
    }
    file_put_contents("respostas.txt", $conteudo, LOCK_EX);
}

function lerUsuarios() {
    if (!file_exists("usuarios.txt")) {
        file_put_contents("usuarios.txt", "id;nome;email\n");
    }
    $linhas = file("usuarios.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $resultado = [];
    for ($i = 1; $i < count($linhas); $i++) {
        $d = explode(";", $linhas[$i], 3);
        if (count($d) == 3) {
            $resultado[] = ["id" => $d[0], "nome" => $d[1], "email" => $d[2]];
        }
    }
    return $resultado;
}

function salvarUsuarios($lista) {
    $conteudo = "id;nome;email\n";
    foreach ($lista as $u) {
        $conteudo .= $u["id"] . ";" . limpar($u["nome"]) . ";" . limpar($u["email"]) . "\n";
    }
    file_put_contents("usuarios.txt", $conteudo, LOCK_EX);
}

$entrada = json_decode(file_get_contents("php://input"), true);
if (!is_array($entrada)) $entrada = $_POST;
$acao = $entrada["acao"] ?? "";

switch ($acao) {
    case "listarPerguntas":
        $perguntas = lerPerguntas();
        $respostas = lerRespostas();
        foreach ($perguntas as &$p) {
            $p["respostas"] = [];
            foreach ($respostas as $r) {
                if ($r["id"] == $p["id"]) {
                    $p["respostas"][] = [
                        "resposta" => $r["resposta"],
                        "correta" => $r["correta"] == "1"
                    ];
                }
            }
        }
        responder(true, $perguntas);

    case "buscarPergunta":
        $id = (string)($entrada["id"] ?? "");
        foreach (lerPerguntas() as $p) {
            if ($p["id"] == $id) {
                $p["respostas"] = [];
                foreach (lerRespostas() as $r) {
                    if ($r["id"] == $id) {
                        $p["respostas"][] = ["resposta" => $r["resposta"], "correta" => $r["correta"] == "1"];
                    }
                }
                responder(true, $p);
            }
        }
        responder(false, null, "Pergunta não encontrada.");

    case "criarPergunta":
    case "alterarPergunta":
        $id = (string)($entrada["id"] ?? "");
        $pergunta = limpar($entrada["pergunta"] ?? "");
        $tipo = $entrada["tipo"] ?? "";
        $respostasEntrada = $entrada["respostas"] ?? [];
        if ($pergunta === "" || !in_array($tipo, ["multipla", "discursiva"]) || !is_array($respostasEntrada) || count($respostasEntrada) == 0) {
            responder(false, null, "Preencha a pergunta e as respostas.");
        }
        if ($tipo == "multipla" && count($respostasEntrada) != 3) {
            responder(false, null, "A pergunta de múltipla escolha precisa ter três respostas.");
        }
        $perguntas = lerPerguntas();
        $respostas = lerRespostas();

        if ($acao == "criarPergunta") {
            $maior = 0;
            foreach ($perguntas as $p) $maior = max($maior, (int)$p["id"]);
            $id = (string)($maior + 1);
            $perguntas[] = ["id" => $id, "pergunta" => $pergunta, "tipo" => $tipo];
        } else {
            $achou = false;
            foreach ($perguntas as &$p) {
                if ($p["id"] == $id) {
                    $p["pergunta"] = $pergunta;
                    $p["tipo"] = $tipo;
                    $achou = true;
                }
            }
            unset($p);
            if (!$achou) responder(false, null, "Pergunta não encontrada.");
            $respostas = array_values(array_filter($respostas, function($r) use ($id) { return $r["id"] != $id; }));
        }

        foreach ($respostasEntrada as $indice => $r) {
            $texto = limpar($r["texto"] ?? "");
            if ($texto === "") responder(false, null, "Todas as respostas precisam ser preenchidas.");
            $correta = !empty($r["correta"]) ? "1" : "0";
            $respostas[] = ["id" => $id, "resposta" => $texto, "correta" => $correta];
        }
        $corretas = 0;
        foreach ($respostas as $r) if ($r["id"] == $id && $r["correta"] == "1") $corretas++;
        if ($corretas != 1) responder(false, null, "Marque exatamente uma resposta correta.");
        salvarPerguntas($perguntas);
        salvarRespostas($respostas);
        responder(true, ["id" => $id], $acao == "criarPergunta" ? "Pergunta cadastrada!" : "Pergunta alterada!");

    case "excluirPergunta":
        $id = (string)($entrada["id"] ?? "");
        $perguntas = array_values(array_filter(lerPerguntas(), function($p) use ($id) { return $p["id"] != $id; }));
        $respostas = array_values(array_filter(lerRespostas(), function($r) use ($id) { return $r["id"] != $id; }));
        salvarPerguntas($perguntas);
        salvarRespostas($respostas);
        responder(true, null, "Pergunta e respostas excluídas!");

    case "listarUsuarios":
        responder(true, lerUsuarios());

    case "criarUsuario":
    case "alterarUsuario":
        $nome = limpar($entrada["nome"] ?? "");
        $email = limpar($entrada["email"] ?? "");
        $usuarios = lerUsuarios();
        if ($nome === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) responder(false, null, "Informe nome e e-mail válido.");
        if ($acao == "criarUsuario") {
            $maior = 0;
            foreach ($usuarios as $u) $maior = max($maior, (int)$u["id"]);
            $id = (string)($maior + 1);
            $usuarios[] = ["id" => $id, "nome" => $nome, "email" => $email];
        } else {
            $id = (string)($entrada["id"] ?? "");
            $achou = false;
            foreach ($usuarios as &$u) {
                if ($u["id"] == $id) {
                    $u["nome"] = $nome;
                    $u["email"] = $email;
                    $achou = true;
                }
            }
            unset($u);
            if (!$achou) responder(false, null, "Usuário não encontrado.");
        }
        salvarUsuarios($usuarios);
        responder(true, ["id" => $id], $acao == "criarUsuario" ? "Usuário cadastrado!" : "Usuário alterado!");

    case "excluirUsuario":
        $id = (string)($entrada["id"] ?? "");
        $usuarios = array_values(array_filter(lerUsuarios(), function($u) use ($id) { return $u["id"] != $id; }));
        salvarUsuarios($usuarios);
        responder(true, null, "Usuário excluído!");

    default:
        responder(false, null, "Ação inválida.");
}
?>
