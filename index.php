
<?php

require_once "conexao.php";

$busca = trim($_GET["busca"] ?? "");

$sql = "SELECT * FROM produtos";
$parametros = [];

if ($busca !== "") {
    $sql .= " WHERE nome LIKE ?
    OR codigo LIKE ?
    OR descricao LIKE ?";

    $termo = "%" . $busca . "%";
    $parametros = [$termo, $termo, $termo];
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

$produtos = $stmt->fetchAll();

function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Estoque</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-slate-100 p-6">

<main class="max-w-6xl mx-auto">

    <header class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">
            Sistema de Controle de Estoque
        </h1>

        <p class="text-slate-500 mt-2">
            Gerencie seus produtos em um só lugar.
        </p>
    </header>

    <?php if (isset($_GET["sucesso"])): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
            Operação realizada com sucesso!
        </div>
    <?php endif; ?>

    <section class="bg-white p-6 rounded-xl shadow-sm mb-6">

        <div class="flex flex-col md:flex-row justify-between gap-4">

            <form method="GET" action="index.php" class="flex flex-1 gap-2">

                <input
                    type="search"
                    name="busca"
                    placeholder="Buscar por nome, código ou descrição..."
                    value="<?= escapar($busca) ?>"
                    class="flex-1 min-w-0 border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                <button
                    type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-3 rounded-lg"
                >
                    Buscar
                </button>

                <?php if ($busca !== ""): ?>
                    <a
                        href="index.php"
                        class="bg-slate-200 hover:bg-slate-300 px-4 py-3 rounded-lg"
                    >
                        Limpar
                    </a>
                <?php endif; ?>

            </form>

            <a
                href="cadastrar.php"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg text-center"
            >
                + Novo Produto
            </a>

        </div>
    </section>

    <section class="bg-white rounded-xl shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-semibold text-slate-800">
                Produtos cadastrados
            </h2>

            <p class="text-slate-500 text-sm mt-1">
                <?= count($produtos) ?> produto(s) encontrado(s).
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 text-slate-600 text-sm">
                    <tr>
                        <th class="p-4">Código</th>
                        <th class="p-4">Nome</th>
                        <th class="p-4">Quantidade</th>
                        <th class="p-4">Preço</th>
                        <th class="p-4">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    <?php if (count($produtos) > 0): ?>

                        <?php foreach ($produtos as $produto): ?>

                            <tr class="hover:bg-slate-50">

                                <td class="p-4">
                                    <?= escapar($produto["codigo"]) ?>
                                </td>

                                <td class="p-4 font-medium text-slate-800">
                                    <?= escapar($produto["nome"]) ?>
                                </td>

                                <td class="p-4">
                                    <?= escapar($produto["quantidade"]) ?>
                                </td>

                                <td class="p-4">
                                    R$ <?= number_format((float) $produto["preco"], 2, ",", ".") ?>
                                </td>

                                <td class="p-4">
                                    <div class="flex flex-wrap gap-2">

                                        <button
                                            type="button"
                                            onclick="verDescricao(this)"
                                            data-descricao="<?= escapar($produto["descricao"]) ?>"
                                            class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-2 rounded-lg"
                                        >
                                            Ver descrição
                                        </button>

                                        <a
                                            href="editar.php?id=<?= escapar($produto["id"]) ?>"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="excluir.php"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este produto?');"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= escapar($produto["id"]) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg"
                                            >
                                                Excluir
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">
                                Nenhum produto encontrado.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>

<div
    id="modalDescricao"
    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4"
>
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg">

        <h2 class="text-xl font-bold text-slate-800 mb-4">
            Descrição do produto
        </h2>

        <p
            id="textoDescricao"
            class="text-slate-600 whitespace-pre-wrap break-words"
        ></p>

        <button
            type="button"
            onclick="fecharDescricao()"
            class="mt-6 bg-slate-800 hover:bg-slate-900 text-white px-5 py-3 rounded-lg"
        >
            Fechar
        </button>

    </div>
</div>

<script>
function verDescricao(botao) {
    const descricao = botao.dataset.descricao;

    document.getElementById("textoDescricao").textContent =
        descricao.trim() !== "" ? descricao : "Nenhuma descrição cadastrada.";

    document.getElementById("modalDescricao").classList.remove("hidden");
}

function fecharDescricao() {
    document.getElementById("modalDescricao").classList.add("hidden");
}
</script>

</body>
</html>
