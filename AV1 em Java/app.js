const API = "api.php";

async function requisicao(acao, dados = {}) {
    const resposta = await fetch(API, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ acao, ...dados })
    });
    const json = await resposta.json();
    if (!json.sucesso) throw new Error(json.mensagem || "Não foi possível concluir a operação.");
    return json;
}

function avisar(mensagem, erro = false) {
    const aviso = document.getElementById("aviso");
    aviso.textContent = mensagem;
    aviso.className = erro ? "erro" : "sucesso";
    setTimeout(() => aviso.textContent = "", 4000);
}

function escapar(texto) {
    return String(texto).replace(/[&<>"']/g, caractere => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    }[caractere]));
}

document.querySelectorAll(".aba").forEach(botao => {
    botao.addEventListener("click", () => {
        document.querySelectorAll(".aba").forEach(b => b.classList.remove("ativa"));
        botao.classList.add("ativa");
        document.getElementById("tela-perguntas").classList.toggle("escondido", botao.dataset.tela !== "perguntas");
        document.getElementById("tela-usuarios").classList.toggle("escondido", botao.dataset.tela !== "usuarios");
    });
});

function mostrarFormularioPergunta(pergunta = null) {
    const form = document.getElementById("formPergunta");
    const tipo = pergunta?.tipo || "multipla";
    const respostas = pergunta?.respostas || [];
    const campos = tipo === "multipla" ? 3 : 1;

    form.classList.remove("escondido");
    form.innerHTML = `
        <h3>${pergunta ? "Alterar pergunta" : "Cadastrar pergunta"}</h3>
        <form id="dadosPergunta">
            <label>Tipo da pergunta</label>
            <select id="tipoPergunta" required>
                <option value="multipla" ${tipo === "multipla" ? "selected" : ""}>Múltipla escolha</option>
                <option value="discursiva" ${tipo === "discursiva" ? "selected" : ""}>Resposta de texto</option>
            </select>
            <label>Pergunta</label>
            <input id="textoPergunta" required value="${escapar(pergunta?.pergunta || "")}">
            <div id="camposRespostas"></div>
            <div class="acoes">
                <button type="submit">${pergunta ? "Salvar alterações" : "Cadastrar"}</button>
                <button type="button" class="secundario" id="cancelarPergunta">Cancelar</button>
            </div>
        </form>`;

    const montarRespostas = () => {
        const selecionado = document.getElementById("tipoPergunta").value;
        const quantidade = selecionado === "multipla" ? 3 : 1;
        const area = document.getElementById("camposRespostas");
        area.innerHTML = "<h4>Respostas</h4>" + Array.from({ length: quantidade }, (_, i) => {
            const r = respostas[i] || {};
            return `
                <div class="resposta-form">
                    <label>Resposta ${i + 1}</label>
                    <input class="textoResposta" required value="${escapar(r.resposta || "")}">
                    <label class="marcar"><input type="radio" name="correta" value="${i}" ${r.correta || (!pergunta && i === 0) ? "checked" : ""}> Correta</label>
                </div>`;
        }).join("");
    };

    montarRespostas();
    document.getElementById("tipoPergunta").addEventListener("change", () => {
        respostas.length = 0;
        montarRespostas();
    });
    document.getElementById("cancelarPergunta").addEventListener("click", () => form.classList.add("escondido"));

    document.getElementById("dadosPergunta").addEventListener("submit", async evento => {
        evento.preventDefault();
        const tipoAtual = document.getElementById("tipoPergunta").value;
        const textos = [...document.querySelectorAll(".textoResposta")];
        const correta = document.querySelector('input[name="correta"]:checked');
        if (!correta) return avisar("Marque a resposta correta.", true);
        const dadosRespostas = textos.map((campo, i) => ({
            texto: campo.value,
            correta: Number(correta.value) === i
        }));
        try {
            const acao = pergunta ? "alterarPergunta" : "criarPergunta";
            const dados = {
                pergunta: document.getElementById("textoPergunta").value,
                tipo: tipoAtual,
                respostas: dadosRespostas
            };
            if (pergunta) dados.id = pergunta.id;
            const resultado = await requisicao(acao, dados);
            avisar(resultado.mensagem);
            form.classList.add("escondido");
            carregarPerguntas();
        } catch (erro) {
            avisar(erro.message, true);
        }
    });
}

async function carregarPerguntas() {
    try {
        const resultado = await requisicao("listarPerguntas");
        const area = document.getElementById("listaPerguntas");
        if (!resultado.dados.length) {
            area.innerHTML = '<p class="vazio">Nenhuma pergunta cadastrada.</p>';
            return;
        }
        area.innerHTML = resultado.dados.map(p => `
            <article class="cartao">
                <div class="cartao-topo"><span>ID ${escapar(p.id)}</span><span>${p.tipo === "multipla" ? "Múltipla escolha" : "Texto"}</span></div>
                <h3>${escapar(p.pergunta)}</h3>
                <ul>${p.respostas.map(r => `<li>${escapar(r.resposta)} ${r.correta ? '<strong class="correta">(correta)</strong>' : ""}</li>`).join("")}</ul>
                <div class="acoes">
                    <button class="editar" data-id="${p.id}">Alterar</button>
                    <button class="perigo excluir" data-id="${p.id}">Excluir</button>
                </div>
            </article>`).join("");

        area.querySelectorAll(".editar").forEach(botao => {
            botao.addEventListener("click", async () => {
                const p = resultado.dados.find(item => item.id === botao.dataset.id);
                mostrarFormularioPergunta(p);
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
        });
        area.querySelectorAll(".excluir").forEach(botao => {
            botao.addEventListener("click", async () => {
                if (!confirm("Deseja excluir esta pergunta e todas as suas respostas?")) return;
                try {
                    const r = await requisicao("excluirPergunta", { id: botao.dataset.id });
                    avisar(r.mensagem);
                    carregarPerguntas();
                    document.getElementById("resultadoBusca").innerHTML = "";
                } catch (erro) { avisar(erro.message, true); }
            });
        });
    } catch (erro) { avisar(erro.message, true); }
}

document.getElementById("novaPergunta").addEventListener("click", () => mostrarFormularioPergunta());
document.getElementById("buscarPergunta").addEventListener("click", async () => {
    const id = document.getElementById("idBusca").value;
    if (!id) return avisar("Digite o ID da pergunta.", true);
    try {
        const r = await requisicao("buscarPergunta", { id });
        const p = r.dados;
        document.getElementById("resultadoBusca").innerHTML = `
            <div class="cartao">
                <h3>${escapar(p.pergunta)}</h3>
                <p>ID: ${escapar(p.id)} | ${p.tipo === "multipla" ? "Múltipla escolha" : "Texto"}</p>
                <ul>${p.respostas.map(resp => `<li>${escapar(resp.resposta)} ${resp.correta ? '<strong class="correta">(correta)</strong>' : ""}</li>`).join("")}</ul>
            </div>`;
    } catch (erro) {
        document.getElementById("resultadoBusca").innerHTML = "";
        avisar(erro.message, true);
    }
});

function mostrarFormularioUsuario(usuario = null) {
    const form = document.getElementById("formUsuario");
    form.classList.remove("escondido");
    form.innerHTML = `
        <h3>${usuario ? "Alterar usuário" : "Cadastrar usuário"}</h3>
        <form id="dadosUsuario">
            <label>Nome</label>
            <input id="nomeUsuario" required value="${escapar(usuario?.nome || "")}">
            <label>E-mail</label>
            <input id="emailUsuario" type="email" required value="${escapar(usuario?.email || "")}">
            <div class="acoes">
                <button type="submit">${usuario ? "Salvar alterações" : "Cadastrar"}</button>
                <button type="button" class="secundario" id="cancelarUsuario">Cancelar</button>
            </div>
        </form>`;
    document.getElementById("cancelarUsuario").addEventListener("click", () => form.classList.add("escondido"));
    document.getElementById("dadosUsuario").addEventListener("submit", async evento => {
        evento.preventDefault();
        try {
            const dados = {
                nome: document.getElementById("nomeUsuario").value,
                email: document.getElementById("emailUsuario").value
            };
            if (usuario) dados.id = usuario.id;
            const r = await requisicao(usuario ? "alterarUsuario" : "criarUsuario", dados);
            avisar(r.mensagem);
            form.classList.add("escondido");
            carregarUsuarios();
        } catch (erro) { avisar(erro.message, true); }
    });
}

async function carregarUsuarios() {
    try {
        const r = await requisicao("listarUsuarios");
        const area = document.getElementById("listaUsuarios");
        if (!r.dados.length) {
            area.innerHTML = '<p class="vazio">Nenhum usuário cadastrado.</p>';
            return;
        }
        area.innerHTML = r.dados.map(u => `
            <article class="cartao usuario">
                <div><strong>${escapar(u.nome)}</strong><p>${escapar(u.email)}</p><small>ID: ${escapar(u.id)}</small></div>
                <div class="acoes">
                    <button class="editarUsuario" data-id="${u.id}">Alterar</button>
                    <button class="perigo excluirUsuario" data-id="${u.id}">Excluir</button>
                </div>
            </article>`).join("");
        area.querySelectorAll(".editarUsuario").forEach(b => b.addEventListener("click", () => {
            mostrarFormularioUsuario(r.dados.find(u => u.id === b.dataset.id));
        }));
        area.querySelectorAll(".excluirUsuario").forEach(b => b.addEventListener("click", async () => {
            if (!confirm("Deseja excluir este usuário?")) return;
            try {
                const resposta = await requisicao("excluirUsuario", { id: b.dataset.id });
                avisar(resposta.mensagem);
                carregarUsuarios();
            } catch (erro) { avisar(erro.message, true); }
        }));
    } catch (erro) { avisar(erro.message, true); }
}

document.getElementById("novoUsuario").addEventListener("click", () => mostrarFormularioUsuario());
carregarPerguntas();
carregarUsuarios();