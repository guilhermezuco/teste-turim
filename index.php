<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Turim</title>
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- STYLE CSS -->
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header text-white">
                        <h1 class="h4 mb-0">Cadastro de Usuários</h1>
                        <img src="img/logo.png" alt="logo">
                    </div>

                    <div class="card-body">
                        <div class="d-flex pb-3 gap-3">
                            <button id="gravarBtn" class="rounded-5 border-0 btn btn-laranja btn-dark btn-lg">
                                Gravar
                            </button>
                            <button id="lerBtn" class="rounded-5 border-0 btn btn-laranja btn-dark btn-lg">
                                Ler
                            </button>
                        </div>
                        <!-- GRUPO BUTTONS -->
                        <div class="input-group mb-4">
                            <input
                                type="text"
                                id="nomeInput"
                                class="form-control form-control-lg"
                                placeholder="Digite o nome do usuário"
                            />
                            <button id="incluirBtn" class="btn btn-success btn-lg">
                                Incluir
                            </button>
                        </div>

                        
                        <div class="row g-4">
                            <!-- TABELA USUÁRIOS -->
                            <div class="col-md-6">
                                <div class="tabela-responsiva">
                                    <table class="table table-hover table-striped" id="usuariosTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Pessoas</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ÁREA JSON -->
                            <div class="col-md-6">
                                <div class="h-100">
                                    <label for="jsonOutput" class="form-label">Json</label>
                                    <textarea
                                        id="jsonOutput"
                                        class="form-control h-100"
                                        readonly
                                        placeholder="JSON"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-center fw-bold">
                        GUILHERME ZUCO ARAÚJO
                    </div>
                </div>

               
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CUSTOM JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const nomeInput = document.getElementById("nomeInput");
            const incluirBtn = document.getElementById("incluirBtn");
            const gravarBtn = document.getElementById("gravarBtn");
            const lerBtn = document.getElementById("lerBtn");
            const usuariosTable = document.querySelector("#usuariosTable tbody");
            const jsonOutput = document.getElementById("jsonOutput");

            let pessoas = [];

            // ADICIONAR PESSOA
            incluirBtn.addEventListener("click", function () {
                const nome = nomeInput.value.trim();

                if (nome) {
                    pessoas.push({ nome: nome });
                    atualizarTabela();
                    atualizarJSON();
                    nomeInput.value = "";
                    nomeInput.focus();
                }
            });

            // GRAVAR NO BANCO
            gravarBtn.addEventListener("click", async function () {
                if (pessoas.length === 0) {
                    alert("Nenhuma pessoa para gravar.");
                    return;
                }

                try {
                    const response = await fetch("api.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ pessoas: pessoas }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert(data.message);
                        pessoas = [];
                        atualizarTabela();
                        atualizarJSON();
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    alert("Erro: " + error.message);
                }
            });

            // LER DO BANCO
            lerBtn.addEventListener("click", async function () {
                try {
                    const response = await fetch("api.php?action=ler");
                    const data = await response.json();

                    pessoas = data.map((item) => ({ nome: item.nome }));

                    atualizarTabela();
                    atualizarJSON();
                } catch (error) {
                    alert("Erro: " + error.message);
                }
            });

            // REMOVER PESSOA PELO NOME
            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("btn-remover")) {
                    const nome = e.target.closest("tr").querySelector("span").textContent;
                    pessoas = pessoas.filter((p) => p.nome !== nome);
                    atualizarTabela();
                    atualizarJSON();
                }
            });

            // ATUALIZAR TABELA
            function atualizarTabela() {
                usuariosTable.innerHTML = "";

                if (pessoas.length === 0) {
                    usuariosTable.innerHTML =
                        '<tr><td colspan="1" class="text-center">Nenhuma pessoa cadastrada</td></tr>';
                    return;
                }

                pessoas.forEach((pessoa) => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                    <td>
                        <div class="nome-cell">
                            <span>${pessoa.nome}</span>
                            <button class="btn btn-sm btn-danger btn-remover">
                                Remover
                            </button>
                        </div>
                    </td>
                `;
                    usuariosTable.appendChild(row);
                });
            }

            // ATUALIZAR JSON
            function atualizarJSON() {
                jsonOutput.value = JSON.stringify(pessoas, null, 2);
            }
        });
    </script>
</body>
</html>
