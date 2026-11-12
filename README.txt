SISTEMA WATER FALLS - VERSÃO COM JAVASCRIPT E FETCH

O que mudou:
- O JavaScript envia as requisições ao PHP usando fetch().
- O PHP recebe os dados em JSON e devolve respostas em JSON.
- A página não precisa recarregar para cadastrar, listar, alterar ou excluir.
- Os dados continuam salvos nos arquivos TXT, sem banco de dados.

Arquivos principais:
- index.php: tela do sistema.
- app.js: ações e requisições feitas pelo JavaScript.
- api.php: recebe as requisições e manipula os arquivos TXT.
- style.css: aparência da página.
- perguntas.txt, respostas.txt e usuarios.txt: armazenamento.

Como executar:
1. Coloque todos os arquivos na mesma pasta dentro do servidor local (por exemplo, htdocs no XAMPP).
2. Inicie o Apache.
3. Abra no navegador: http://localhost/sistema_water_falls_js/
4. Não abra o index.php clicando duas vezes no arquivo: PHP precisa ser executado por um servidor.

Formato dos arquivos:
perguntas.txt: id;pergunta;tipo
respostas.txt: id_pergunta;resposta;correta
usuarios.txt: id;nome;email

Atenção:
- Nas perguntas de múltipla escolha, são cadastradas três alternativas e uma deve ser marcada como correta.
- Nas perguntas de texto, existe uma resposta esperada, marcada como correta.
- Para manter o formato TXT simples, o sistema troca ponto e vírgula digitado nos campos por vírgula.
