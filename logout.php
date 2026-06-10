<?php
require 'config.php';

if (isset($_SESSION['usuario_id'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt_log = $pdo->prepare("INSERT INTO logs (usuario_id, evento, endereco_ip) VALUES (?, 'LOGOUT', ?)");
    $stmt_log->execute([$_SESSION['usuario_id'], $ip]);
}

$_SESSION = array();
session_destroy();
header("Location: index.php");
exit;
?>