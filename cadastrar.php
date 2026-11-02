
<?php

require_once "conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $codigo = trim($_POST["codigo"] ?? "");
    $nome = trim($_POST["nome"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");

    $quantidade = filter_var($_POST["quantidade"] ?? null, FILTER_VALIDATE_INT);
    $preco = filter_var($_POST["preco"] ?? null, FILTER_VALIDATE_FLOAT);

    if (
        $codigo === "" ||
        $nome === "" ||
        $quantidade === false ||
        $quantidade === null ||
        $quantidade < 0 ||
        $preco === false ||
        $preco === null ||
        $preco < 0
    ) {
        $mensagem = "Preencha os campos corretamente.";

    } else {

        try {

            $sql = "INSERT INTO produtos
                    (codigo, nome, descricao, quantidade, preco)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $codigo,
                $nome,
                $descricao,
                $quantidade,
                $preco
            ]);

            header("Location: index.php?sucesso=cadastro");
            exit;

        } catch (PDOException $e) {

            if ($e->getCode() === "23000") {
                $mensagem = "Esse código já está cadastrado.";
            } else {
                $mensagem = "Não foi possível cadastrar o produto.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">

    <main class="w-full max-w-xl bg-white rounded-2xl shadow-lg p-8">

        <h1 class="text-3xl font-bold text-slate-800 mb-2">
            Cadastrar Produto
        </h1>

        <p class="text-slate-500 mb-8">
            Preencha os dados para adicionar um produto ao estoque.
        </p>

        <?php if ($mensagem !== ""): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">

            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    Código
                </label>

                <input
                    type="text"
                    name="codigo"
                    maxlength="30"
                    required
                    class="w-full border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    Nome
                </label>

                <input
                    type="text"
                    name="nome"
                    maxlength="100"
                    required
                    class="w-full border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    Descrição
                </label>

                <textarea
                    name="descricao"
                    rows="3"
                    class="w-full border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    Quantidade
                </label>

                <input
                    type="number"
                    name="quantidade"
                    min="0"
                    required
                    class="w-full border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-2">
                    Preço
                </label>

                <input
                    type="number"
                    name="preco"
                    min="0"
                    step="0.01"
                    required
                    class="w-full border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition"
            >
                Cadastrar Produto
            </button>

        </form>

        <a
            href="index.php"
            class="block text-center text-blue-600 hover:underline mt-6"
        >
            Voltar para a listagem
        </a>

    </main>

</body>
</html>
