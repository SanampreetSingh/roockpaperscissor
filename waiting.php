<?php
session_start();

$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room'] ?? '');
$player = $_GET['player'] ?? '';
$opponent = $_GET['opponent'] ?? '';

if (empty($room) || empty($player)) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

// Check if room exists and opponent reconnected
if (isset($_SESSION['rooms'][$room])) {
    foreach ($_SESSION['rooms'][$room]['players'] as $id => $p) {
        if ($id !== $player && (time() - ($p['last_active'] ?? 0)) < 30) {
            header("Location: play_online.php?room=$room&id=$player&opponent=".urlencode($p['name']));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Opponent Disconnected</title>
    <meta http-equiv="refresh" content="5">
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #6a11cb;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <h2>Opponent Disconnected</h2>
    <p>Waiting for <?= htmlspecialchars($opponent) ?> to reconnect...</p>
    <p>Room: <strong><?= htmlspecialchars($room) ?></strong></p>
    <div class="spinner"></div>
    <p>This page refreshes automatically every 5 seconds</p>
    <a href="index.php">Leave Game</a>
</body>
</html>
