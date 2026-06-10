<?php
require 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: portal.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $consentimento = isset($_POST['consentimento_marketing']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, consentimento_marketing = ? WHERE id = ?");
    try {
        $stmt->execute([$nome, $email, $consentimento, $usuario_id]);
        $_SESSION['usuario_nome'] = $nome;
        $mensagem = "Dados atualizados com sucesso!";
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar. O e-mail informado pode já estar em uso.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['excluir'])) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    
    $_SESSION = array();
    session_destroy();
    header("Location: portal.php?msg=conta_excluida");
    exit;
}

$stmt = $pdo->prepare("SELECT nome, email, consentimento_marketing FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$user_data = $stmt->fetch();

require 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm mt-4">
            <h2 class="fw-bold mb-4">Gerenciar Minha Conta</h2>

            <?php if(!empty($mensagem)) echo "<div class='alert alert-success'>$mensagem</div>"; ?>
            <?php if(!empty($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>

            <form method="POST" class="mb-4">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($user_data['nome']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_data['email']) ?>" required>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" name="consentimento_marketing" class="form-check-input" id="marketing" value="1" <?= $user_data['consentimento_marketing'] == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label text-muted small" for="marketing">Desejo receber e-mails de campanhas de marketing e ofertas de cursos. Desmarque para revogar o consentimento. </label>
                </div>

                <button type="submit" name="atualizar" class="btn btn-primary w-100 fw-bold">Salvar Alterações</button>
            </form>

            <hr>

            <div class="bg-light p-3 rounded border border-danger">
                <h5 class="text-danger fw-bold">Deletar Conta</h5>
                <p class="text-muted small">Ao excluir sua conta, todos os seus dados e históricos de cursos serão removidos permanentemente de acordo com as diretrizes da LGPD.</p>
                <form method="POST" onsubmit="return confirm('Tem certeza absoluta que deseja excluir sua conta? Esta ação não pode ser desfeita.');">
                    <button type="submit" name="excluir" class="btn btn-danger w-100 fw-bold">Excluir Minha Conta</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>