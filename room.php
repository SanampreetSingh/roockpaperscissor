<?php
session_start();

$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room'] ?? '');
$player = $_GET['id'] ?? '';

if (empty($room) || empty($player)) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

// Check if room exists in session
if (!isset($_SESSION['rooms'][$room])) {
    header("Location: online.php?error=room_not_found");
    exit;
}

// Update player activity
$_SESSION['rooms'][$room]['players'][$player]['last_active'] = time();

// Check if opponent has joined
if (count($_SESSION['rooms'][$room]['players']) === 2) {
    $opponent = '';
    foreach ($_SESSION['rooms'][$room]['players'] as $id => $p) {
        if ($id !== $player) {
            $opponent = $p['name'];
            break;
        }
    }
    header("Location: play_online.php?room=$room&id=$player&opponent=".urlencode($opponent));
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Waiting Room</title>
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
    <h2>Waiting for opponent...</h2>
    <p>Room: <strong><?= htmlspecialchars($room) ?></strong></p>
    <p>Connected players: <?= count($_SESSION['rooms'][$room]['players']) ?>/2</p>
    <div class="spinner"></div>
    <p>This page refreshes automatically every 5 seconds</p>
    <a href="index.php">Cancel</a>
</body>
</html>
