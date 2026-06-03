<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Plataforma de Cursos</title>
    <link rel="shortcut icon" href="graduation-cap-white.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background-color: #fdfcf9; color: #000000; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
            .navbar { background-color: #fdfcf9; border-bottom: 1px solid #e0e0e0; }
            .btn-acessar { background-color: #0d6efd; color: #ffffff; border-radius: 20px; padding: 5px 20px; text-decoration: none; }
            .nav-link { color: #000000; }
        </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <div class="navbar-nav">
            <img src="graduation-cap-black.svg" alt="chapéu de um estudante na formatura">
            <a href="index.php" class="fw-bold nav-link">Courses</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <a href="portal.php" class="btn btn-acessar m-2">Acessar Plataforma →</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li class="navbar-text text-dark m-2">Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong></li>
                        <li class="navbar-text text-dark m-0"><a class="nav-link text-primary fw-bold ms-2" href="perfil.php">Meu Perfil</a></li>
                        <li class="navbar-text text-dark m-0"><a class="nav-link text-danger fw-bold" href="logout.php">Sair</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">