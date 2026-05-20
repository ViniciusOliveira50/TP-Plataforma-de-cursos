<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->execute([$email, $senha]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos.";
    }
}
require 'header.php';
?>

<h2 class="fw-bold" >Login</h2>
<?php 
if(isset($_GET['msg'])) echo "<div class='alert alert-success'>Cadastro realizado! Faça login.</div>";
if(isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; 
?>

<form method="POST" class="w-50">
    <div class="mb-3">
        <label>E-mail</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="senha" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary fw-bold">Entrar</button>
</form>

<?php require 'footer.php'; ?>