<?php
require 'config.php';

$erro_inscricao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscrever_curso_id'])) {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: portal.php");
        exit;
    }
    
    $usuario_id = $_SESSION['usuario_id'];
    $curso_id = $_POST['inscrever_curso_id'];

    $stmt_check = $pdo->prepare("SELECT id FROM matriculas WHERE usuario_id = ? AND curso_id = ?");
    $stmt_check->execute([$usuario_id, $curso_id]);
    
    if ($stmt_check->rowCount() > 0) {
        $erro_inscricao = "Você já está inscrito neste curso! Acesse 'Meus Cursos' para acessá-lo.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO matriculas (usuario_id, curso_id) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $curso_id]);
        header("Location: meus_cursos.php?msg=inscrito");
        exit;
    }
}

require 'header.php';

$stmt = $pdo->query("SELECT * FROM cursos");
$cursos = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold" style="color: #2c3e1e;">Nossos Cursos</h2>
            <p class="text-muted">Aprenda com os melhores especialistas do mercado.</p>
        </div>
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <a href="meus_cursos.php" class="btn btn-outline-success fw-bold">Ir para Meus Cursos →</a>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($erro_inscricao)) echo "<div class='alert alert-warning fw-bold'>$erro_inscricao</div>"; ?>

<div class="row">
    <?php foreach ($cursos as $curso): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden; background-color: #ffffff;">
                <img src="<?= htmlspecialchars($curso['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($curso['titulo']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold" style="color: #3a4a2a;"><?= htmlspecialchars($curso['titulo']) ?></h5>
                    <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars($curso['descricao']) ?></p>
                </div>
                <div class="card-footer bg-white border-0 pt-0 pb-3">
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <form method="POST">
                            <input type="hidden" name="inscrever_curso_id" value="<?= $curso['id'] ?>">
                            <button type="submit" class="btn btn-success w-100 fw-bold" style="border-radius: 10px;">Inscrever-se</button>
                        </form>
                    <?php else: ?>
                        <a href="portal.php" class="btn btn-outline-primary w-100 fw-bold" style="border-radius: 10px;">Acesse para se inscrever</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require 'footer.php'; ?>