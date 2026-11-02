
<?php

require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: index.php");
    exit;
}

$sql = "DELETE FROM produtos WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: index.php?sucesso=exclusao");
exit;

?>
