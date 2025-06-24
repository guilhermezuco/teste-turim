// CARREGA TODAS AS PESSOAS DO BANCO
async function carregarPessoas() {
    const resp = await fetch('api.php');
    const data = await resp.json();
    atualizaInterface(data);
}

// ADICIONA NOVA PESSOA
async function incluirUsuario() {
    const nome = document.getElementById('nomeInput').value.trim();
    if (!nome) {
        alert('Informe o nome!');
        return;
    }
    const resp = await fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'}, 
        body: JSON.stringify({ nome: nome })
    });
    const data = await resp.json();
    atualizaInterface(data);
    document.getElementById('nomeInput').value = '';
}

// EXCLUI PESSOA POR NOME
async function excluirUsuario(nome) {
    const resp = await fetch('api.php', {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'}, 
        body: JSON.stringify({ nome: nome })
    });
    const data = await resp.json();
    atualizaInterface(data);
}

// ATUALIZA TABELA E JSON
function atualizaInterface(data) {
    const tabela = document.querySelector('#usuariosTable tbody');
    const jsonOutput = document.querySelector('#jsonOutput');

    tabela.innerHTML = '';
    data.forEach(u => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="d-flex justify-content-between align-items-center">
                <span>${u.nome}</span>
                <button class="btn btn-danger btn-sm">Remover</button>
            </td>
        `;
        const botaoRemover = tr.querySelector('button');
        botaoRemover.addEventListener('click', async () => {
            await excluirUsuario(u.nome);
        });
        tabela.appendChild(tr);
    });
    jsonOutput.value = JSON.stringify(data, null, 2);
}

// EVENT
document.getElementById('incluirBtn').addEventListener('click', incluirUsuario);
document.getElementById('lerBtn').addEventListener('click', carregarPessoas);

// CARREGAR AO ABRIR
carregarPessoas();
