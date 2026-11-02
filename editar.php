
<?php

require_once "conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: index.php");
    exit;
}

// Buscar produto pelo ID
$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

$produto = $stmt->fetch();

if (!$produto) {
    header("Location: index.php");
    exit;
}

$erro = "";

// Atualizar produto
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $codigo = trim($_POST["codigo"] ?? "");
    $nome = trim($_POST["nome"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $quantidade = filter_var($_POST["quantidade"] ?? null, FILTER_VALIDATE_INT);
    $preco = $_POST["preco"] ?? "";

    if (
        $codigo === "" ||
        $nome === "" ||
        $quantidade === false ||
        $quantidade === null ||
        $quantidade < 0 ||
        !is_numeric($preco) ||
        (float) $preco < 0
    ) {
        $erro = "Preencha os campos corretamente.";
    } else {

        try {

            $sql = "UPDATE produtos SET
                    codigo = ?,
                    nome = ?,
                    descricao = ?,
                    quantidade = ?,
                    preco = ?
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $codigo,
                $nome,
                $descricao,
                $quantidade,
                $preco,
                $id
            ]);

            header("Location: index.php?sucesso=edicao");
            exit;

        } catch (PDOException $e) {

            if ($e->getCode() === "23000") {
                $erro = "Esse código já está cadastrado em outro produto.";
            } else {
                $erro = "Erro ao atualizar o produto.";
            }

        }
    }

    // Manter os valores digitados caso ocorra erro
    $produto["codigo"] = $codigo;
    $produto["nome"] = $nome;
    $produto["descricao"] = $descricao;
    $produto["quantidade"] = $_POST["quantidade"] ?? "";
    $produto["preco"] = $preco;
}

function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-slate-100 p-6">

    <main class="max-w-2xl mx-auto">

        <header class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                Editar Produto
            </h1>

            <p class="text-slate-500 mt-2">
                Atualize as informações do produto.
            </p>
        </header>

        <section class="bg-white p-6 rounded-xl shadow-sm">

            <?php if ($erro !== ""): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                    <?= escapar($erro) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">

                <div>
                    <label class="block text-slate-700 font-medium mb-2">
                        Código
                    </label>

                    <input
                        type="text"
                        name="codigo"
                        value="<?= escapar($produto["codigo"]) ?>"
                        required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-slate-700 font-medium mb-2">
                        Nome
                    </label>

                    <input
                        type="text"
                        name="nome"
                        value="<?= escapar($produto["nome"]) ?>"
                        required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-slate-700 font-medium mb-2">
                        Descrição
                    </label>

                    <textarea
                        name="descricao"
                        rows="3"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ><?= escapar($produto["descricao"]) ?></textarea>
                </div>

                <div>
                    <label class="block text-slate-700 font-medium mb-2">
                        Quantidade
                    </label>

                    <input
                        type="number"
                        name="quantidade"
                        min="0"
                        value="<?= escapar($produto["quantidade"]) ?>"
                        required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-slate-700 font-medium mb-2">
                        Preço
                    </label>

                    <input
                        type="number"
                        name="preco"
                        min="0"
                        step="0.01"
                        value="<?= escapar($produto["preco"]) ?>"
                        required
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg"
                    >
                        Salvar Alterações
                    </button>

                    <a
                        href="index.php"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-5 py-3 rounded-lg"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

        </section>

    </main>

</body>
</html>
