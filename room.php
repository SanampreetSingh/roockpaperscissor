<?php
session_start();
$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room'] ?? '');
$player = $_GET['id'] ?? '';
if (empty($room) || empty($player)) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}
// Fix: Verify room exists in session
if (empty($_SESSION['rooms'][$room])) {
    header("Location: online.php?error=room_not_found");
    exit;
}
// Update player activity
$_SESSION['rooms'][$room]['players'][$player]['last_active'] = time();
// Fix: Count only active players (not timed out)
$active_players = 0;
foreach ($_SESSION['rooms'][$room]['players'] as $p) {
    if (time() - $p['last_active'] < 30) {
        $active_players++;
    }
}

// If we have two active players, start the game
if ($active_players === 2) {
    foreach ($_SESSION['rooms'][$room]['players'] as $id => $p) {
        if ($id !== $player) {
            $opponent = $p['name'];
            header("Location: play_online.php?room=$room&id=$player&opponent=".urlencode($opponent));
            exit;
        }
    }
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
        <p>Connected ACTIVE players: <?= $active_players ?>/2</p>
    <h2>Waiting for opponent...</h2>
    <p>Room: <strong><?= htmlspecialchars($room) ?></strong></p>
    <p>Connected players: <?= count($_SESSION['rooms'][$room]['players']) ?>/2</p>
    <div class="spinner"></div>
    <p>This page refreshes automatically every 5 seconds</p>
    <a href="index.php">Cancel</a>
</body>
</html>
