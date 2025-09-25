<?php include 'includes/header.php'; ?>

<h2>Mensagem recebida</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['email']);
    $mensagem = htmlspecialchars($_POST['mensagem']);

    echo "<p><strong>Nome:</strong> $nome</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Mensagem:</strong> $mensagem</p>";
} else {
    echo "<p>Nenhum dado enviado.</p>";
}
?>

<?php include 'includes/footer.php'; ?>