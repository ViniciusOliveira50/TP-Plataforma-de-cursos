<?php
require 'config.php';
require 'header.php';

$stmt = $pdo->query("SELECT * FROM cursos");
$cursos = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold" style="color: #2c3e1e;">Nossos Cursos</h2>
        <p class="text-muted">Aprenda com os melhores especialistas do mercado.</p>
    </div>
</div>

<div class="row">
    <?php foreach ($cursos as $curso): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden; background-color: #ffffff;">
                
                <img src="<?= htmlspecialchars($curso['imagem']) ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($curso['titulo']) ?>" 
                     style="height: 200px; object-fit: cover;">
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold" style="color: #3a4a2a;"><?= htmlspecialchars($curso['titulo']) ?></h5>
                    <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars($curso['descricao']) ?></p>
                </div>
                
                <div class="card-footer bg-white border-0 pt-0 pb-3">
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <button class="btn btn-success w-100 fw-bold" style="border-radius: 10px;">Inscrever-se</button>
                    <?php else: ?>
                        <a href="portal.php" class="btn btn-outline-primary w-100 fw-bold" style="border-radius: 10px;">Acesse para se inscrever</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require 'footer.php'; ?>