<?php
session_start();

$room = substr(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room'] ?? ''), 0, 20);
$player_id = $_GET['player'] ?? '';
$opponent = $_GET['opponent'] ?? '';

if (empty($room) || empty($player_id) || !isset($_SESSION['rooms'][$room])) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

// Check if opponent reconnected
if (isset($_SESSION['rooms'][$room])) {
    foreach ($_SESSION['rooms'][$room]['players'] as $id => $player) {
        if ($id !== $player_id && (time() - ($player['last_active'] ?? 0)) < 30) {
            header("Location: play_online.php?room=".urlencode($room)."&player_id=".urlencode($player_id)."&opponent=".urlencode($player['name']));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Waiting</title>
    <meta http-equiv="refresh" content="5">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 500px;
        }
        .spinner {
            border: 4px solid rgba(0,0,0,0.1);
            border-radius: 50%;
            border-top: 4px solid #6a11cb;
            width: 40px;
            height: 40px;
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
    <div class="container">
        <h2>Opponent Disconnected</h2>
        <p>Waiting for <?= htmlspecialchars($opponent) ?> to reconnect...</p>
        <div class="spinner"></div>
        <p>This page refreshes automatically every 5 seconds</p>
        <a href="index.php">Leave Game</a>
    </div>
</body>
</html>
