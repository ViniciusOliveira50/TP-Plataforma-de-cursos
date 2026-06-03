<?php
require 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: portal.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['desinscrever'])) {
    $matricula_id = $_POST['matricula_id'];
    
    $stmt = $pdo->prepare("DELETE FROM matriculas WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$matricula_id, $usuario_id]);
    
    header("Location: meus_cursos.php?msg=removido");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_progresso'])) {
    $matricula_id = $_POST['matricula_id'];
    $novo_progresso = $_POST['progresso_valor'];

    $stmt = $pdo->prepare("UPDATE matriculas SET progresso = ? WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$novo_progresso, $matricula_id, $usuario_id]);
    
    header("Location: meus_cursos.php?msg=progresso_ok");
    exit;
}

$stmt = $pdo->prepare("
    SELECT m.id AS matricula_id, m.progresso, c.titulo, c.descricao, c.imagem 
    FROM matriculas m 
    JOIN cursos c ON m.curso_id = c.id 
    WHERE m.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$minhas_matriculas = $stmt->fetchAll();

require 'header.php';
?>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">Meus Cursos Inscritos</h2>
        <p class="text-muted">Acompanhe seu progresso e evolução acadêmica.</p>
    </div>
</div>

<?php 
if(isset($_GET['msg']) && $_GET['msg'] == 'inscrito') echo "<div class='alert alert-success'>Inscrição realizada com sucesso!</div>";
if(isset($_GET['msg']) && $_GET['msg'] == 'removido') echo "<div class='alert alert-danger'>Inscrição cancelada. O curso foi removido da sua lista.</div>";
if(isset($_GET['msg']) && $_GET['msg'] == 'progresso_ok') echo "<div class='alert alert-success'>Progresso atualizado!</div>";
?>

<?php if (empty($minhas_matriculas)): ?>
    <div class="alert alert-info">Você ainda não se inscreveu em nenhum curso. <a href="index.php">Veja os cursos disponíveis</a>.</div>
<?php else: ?>
    <div class="row">
        <?php foreach ($minhas_matriculas as $item): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; overflow: hidden;">
                    <div class="row g-0 h-100">
                        <div class="col-md-4">
                            <img src="<?= htmlspecialchars($item['imagem']) ?>" class="img-fluid h-100" alt="..." style="object-fit: cover; min-height: 150px;">
                        </div>
                        <div class="col-md-8 d-flex flex-column">
                            <div class="card-body pb-0">
                                <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($item['titulo']) ?></h5>
                                
                                <div class="mt-3">
                                    <label class="form-label small fw-bold mb-1">Progresso: <?= $item['progresso'] ?>%</label>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $item['progresso'] ?>%;" aria-valuenow="<?= $item['progresso'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0 pt-3 pb-3 d-flex justify-content-between align-items-center">
                                
                                <form method="POST" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="matricula_id" value="<?= $item['matricula_id'] ?>">
                                    <select name="progresso_valor" class="form-select form-select-sm" style="width: 100px;">
                                        <option value="0" <?= $item['progresso'] == 0 ? 'selected' : '' ?>>0%</option>
                                        <option value="25" <?= $item['progresso'] == 25 ? 'selected' : '' ?>>25%</option>
                                        <option value="50" <?= $item['progresso'] == 50 ? 'selected' : '' ?>>50%</option>
                                        <option value="75" <?= $item['progresso'] == 75 ? 'selected' : '' ?>>75%</option>
                                        <option value="100" <?= $item['progresso'] == 100 ? 'selected' : '' ?>>100%</option>
                                    </select>
                                    <button type="submit" name="atualizar_progresso" class="btn btn-sm btn-outline-secondary fw-bold">Atualizar</button>
                                </form>

                                <form method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar sua inscrição neste curso? Seu progresso será perdido.');">
                                    <input type="hidden" name="matricula_id" value="<?= $item['matricula_id'] ?>">
                                    <button type="submit" name="desinscrever" class="btn btn-sm btn-outline-danger" title="Desinscrever do Curso">Cancelar inscrição</button>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require 'footer.php'; ?>