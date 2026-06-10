<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); 
    $consentimento = isset($_POST['consentimento_marketing']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, consentimento_marketing) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$nome, $email, $senha, $consentimento]);
        header("Location: login.php?msg=sucesso");
        exit;
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar. O e-mail pode já estar em uso.";
    }
}
require 'header.php';
?>

<h2 class="fw-bold">Cadastro</h2>
<?php if(isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>

<form method="POST" class="w-50">
    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>E-mail</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="senha" class="form-control" required>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" name="consentimento_marketing" class="form-check-input" id="marketing" value="1">
        <label class="form-check-label text-muted small" for="marketing">Desejo receber e-mails de campanhas de marketing e ofertas de novos cursos.</label>
    </div>

    <button type="submit" class="btn btn-success fw-bold">Cadastrar</button>
</form>

<?php require 'footer.php'; ?>