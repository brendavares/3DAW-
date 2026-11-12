<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Water Falls</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <header>
            <h1>Sistema Water Falls</h1>
            <p>Cadastro de perguntas, respostas e usuários</p>
        </header>

        <nav class="abas">
            <button class="aba ativa" data-tela="perguntas">Perguntas</button>
            <button class="aba" data-tela="usuarios">Usuários</button>
        </nav>

        <section id="tela-perguntas">
            <div class="barra">
                <h2>Perguntas</h2>
                <button id="novaPergunta">+ Nova pergunta</button>
            </div>
            <div id="formPergunta" class="painel escondido"></div>
            <div id="listaPerguntas"></div>
            <div id="consultaPergunta" class="painel">
                <h3>Buscar uma pergunta</h3>
                <div class="linha">
                    <input type="number" id="idBusca" min="1" placeholder="ID da pergunta">
                    <button id="buscarPergunta">Buscar</button>
                </div>
                <div id="resultadoBusca"></div>
            </div>
        </section>

        <section id="tela-usuarios" class="escondido">
            <div class="barra">
                <h2>Usuários</h2>
                <button id="novoUsuario">+ Novo usuário</button>
            </div>
            <div id="formUsuario" class="painel escondido"></div>
            <div id="listaUsuarios"></div>
        </section>

        <p id="aviso" role="status"></p>
    </main>
    <script src="app.js"></script>
</body>
</html>
