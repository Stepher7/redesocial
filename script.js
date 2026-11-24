const api = "api.php?rota=";
let usuarioAtual = JSON.parse(localStorage.getItem("conecta_usuario") || "null");

const telaLogin = document.getElementById("tela-login");
const aplicacao = document.getElementById("aplicacao");
const formLogin = document.getElementById("form-login");
const formCadastro = document.getElementById("form-cadastro");
const mensagemAuth = document.getElementById("mensagem-auth");
const listaPublicacoes = document.getElementById("lista-publicacoes");

document.getElementById("mostrar-cadastro").addEventListener("click", () => {
    formLogin.classList.add("escondido");
    formCadastro.classList.remove("escondido");
    mensagemAuth.textContent = "";
});

document.getElementById("mostrar-login").addEventListener("click", () => {
    formCadastro.classList.add("escondido");
    formLogin.classList.remove("escondido");
    mensagemAuth.textContent = "";
});

async function requisicao(rota, metodo = "GET", dados = null) {
    const opcoes = { method: metodo, headers: { "Content-Type": "application/json" } };
    if (dados) opcoes.body = JSON.stringify(dados);

    const resposta = await fetch(api + rota, opcoes);
    const resultado = await resposta.json();

    if (!resposta.ok) {
        throw new Error(resultado.erro || "Não foi possível concluir a ação.");
    }

    return resultado;
}

function mostrarMensagem(texto, erro = true) {
    mensagemAuth.textContent = texto;
    mensagemAuth.style.color = erro ? "#c04458" : "#39865b";
}

function entrar(usuario) {
    usuarioAtual = usuario;
    localStorage.setItem("conecta_usuario", JSON.stringify(usuario));
    telaLogin.classList.add("escondido");
    aplicacao.classList.remove("escondido");

    const avatar = "https://ui-avatars.com/api/?name=" + encodeURIComponent(usuario.nome) + "&background=7658d6&color=fff";
    document.getElementById("nome-topo").textContent = usuario.nome;
    document.getElementById("nome-menu").textContent = usuario.nome;
    document.getElementById("usuario-menu").textContent = "@" + usuario.username;
    document.getElementById("nome-criador").textContent = usuario.nome;
    document.getElementById("avatar-menu").src = avatar;
    document.getElementById("avatar-post").src = avatar;

    carregarFeed();
}

formLogin.addEventListener("submit", async (evento) => {
    evento.preventDefault();
    const dados = {
        login: document.getElementById("login").value.trim(),
        senha: document.getElementById("senha-login").value
    };

    try {
        const resultado = await requisicao("login", "POST", dados);
        entrar(resultado.usuario);
    } catch (erro) {
        mostrarMensagem(erro.message);
    }
});

formCadastro.addEventListener("submit", async (evento) => {
    evento.preventDefault();
    const dados = {
        nome: document.getElementById("nome").value.trim(),
        username: document.getElementById("username").value.trim().replace("@", ""),
        email: document.getElementById("email").value.trim(),
        senha: document.getElementById("senha-cadastro").value
    };

    try {
        await requisicao("usuario", "POST", dados);
        const resultado = await requisicao("login", "POST", {
            login: dados.email,
            senha: dados.senha
        });
        entrar(resultado.usuario);
    } catch (erro) {
        mostrarMensagem(erro.message);
    }
});

async function carregarFeed() {
    listaPublicacoes.innerHTML = '<p class="carregando">Carregando publicações...</p>';

    try {
        const publicacoes = await requisicao("publicacao&id_usuario=" + usuarioAtual.id_usuario);
        if (!publicacoes.length) {
            listaPublicacoes.innerHTML = '<div class="vazio">Ainda não tem publicações por aqui. Que tal fazer a primeira?</div>';
            return;
        }

        listaPublicacoes.innerHTML = publicacoes.map(publicacao => {
            const nome = escapar(publicacao.nome);
            const username = escapar(publicacao.username);
            const avatar = "https://ui-avatars.com/api/?name=" + encodeURIComponent(publicacao.nome) + "&background=eeeafd&color=6043bd";
            const imagem = publicacao.imagem_url
                ? '<img class="imagem-publicacao" src="' + escapar(publicacao.imagem_url) + '" alt="Imagem da publicação" onerror="this.remove()">'
                : "";
            const curtiu = Number(publicacao.curtiu) > 0;

            return `
                <article class="publicacao">
                    <div class="publicacao-topo">
                        <div class="usuario-post">
                            <img src="${avatar}" alt="">
                            <div><strong>${nome}</strong><small>@${username}</small></div>
                        </div>
                        <span class="data-post">${formatarData(publicacao.criado_em)}</span>
                    </div>
                    ${imagem}
                    <p class="texto-publicacao">${escapar(publicacao.conteudo)}</p>
                    <div class="acoes-publicacao">
                        <button class="botao-curtir ${curtiu ? "curtiu" : ""}" data-id="${publicacao.id_publicacao}" aria-label="Curtir publicação">${curtiu ? "♥" : "♡"}</button>
                        <span class="total-curtidas">${publicacao.total_curtidas} curtidas</span>
                    </div>
                </article>
            `;
        }).join("");

        document.querySelectorAll(".botao-curtir").forEach(botao => {
            botao.addEventListener("click", () => alternarCurtida(botao));
        });
    } catch (erro) {
        listaPublicacoes.innerHTML = '<div class="vazio">Não foi possível carregar o feed. Confira a conexão com a API.</div>';
    }
}

async function alternarCurtida(botao) {
    const idPublicacao = Number(botao.dataset.id);
    const jaCurtiu = botao.classList.contains("curtiu");
    const dados = {
        id_usuario: usuarioAtual.id_usuario,
        id_publicacao: idPublicacao
    };

    try {
        if (jaCurtiu) {
            await requisicao("curtida", "DELETE", dados);
        } else {
            await requisicao("curtida", "POST", dados);
        }
        carregarFeed();
    } catch (erro) {
        alert(erro.message);
    }
}

document.getElementById("form-publicacao").addEventListener("submit", async (evento) => {
    evento.preventDefault();
    const conteudo = document.getElementById("conteudo-post").value.trim();
    const imagem = document.getElementById("imagem-post").value.trim();

    if (!conteudo) return;

    try {
        await requisicao("publicacao", "POST", {
            id_usuario: usuarioAtual.id_usuario,
            conteudo: conteudo,
            imagem_url: imagem
        });
        document.getElementById("conteudo-post").value = "";
        document.getElementById("imagem-post").value = "";
        carregarFeed();
    } catch (erro) {
        alert(erro.message);
    }
});

function formatarData(data) {
    if (!data) return "agora";
    const valor = new Date(data.replace(" ", "T"));
    if (Number.isNaN(valor.getTime())) return "agora";
    return valor.toLocaleString("pt-BR", { day: "2-digit", month: "short", hour: "2-digit", minute: "2-digit" });
}

function escapar(valor) {
    return String(valor ?? "").replace(/[&<>"']/g, caractere => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;"
    })[caractere]);
}

document.getElementById("sair").addEventListener("click", () => {
    localStorage.removeItem("conecta_usuario");
    usuarioAtual = null;
    aplicacao.classList.add("escondido");
    telaLogin.classList.remove("escondido");
    formLogin.reset();
});

if (usuarioAtual) entrar(usuarioAtual);
