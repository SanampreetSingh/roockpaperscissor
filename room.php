<?php
session_start();

$room = substr(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room'] ?? ''), 0, 20);
$player_id = $_GET['player_id'] ?? '';

if (empty($room) || empty($player_id) || !isset($_SESSION['rooms'][$room])) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

// Update player activity
if (isset($_SESSION['rooms'][$room]['players'][$player_id])) {
    $_SESSION['rooms'][$room]['players'][$player_id]['last_active'] = time();
}

// Count active players and find opponent
$active_players = 0;
$opponent_name = '';
foreach ($_SESSION['rooms'][$room]['players'] as $id => $player) {
    if (time() - $player['last_active'] < 30) {
        $active_players++;
        if ($id !== $player_id) {
            $opponent_name = $player['name'];
        }
    }
}

// Redirect to game if both players are active
if ($active_players === 2) {
    header("Location: play_online.php?room=".urlencode($room)."&player_id=".urlencode($player_id)."&opponent=".urlencode($opponent_name));
    exit;
}

// Clean up inactive players
foreach ($_SESSION['rooms'][$room]['players'] as $id => $player) {
    if (time() - $player['last_active'] >= 30) {
        unset($_SESSION['rooms'][$room]['players'][$id]);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Waiting for Opponent</title>
    <meta http-equiv="refresh" content="5">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
        a {
            display: inline-block;
            margin-top: 1rem;
            color: #6a11cb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Waiting Room: <?= htmlspecialchars($room) ?></h2>
        <p>Players ready: <?= $active_players ?>/2</p>
        <div class="spinner"></div>
        <p>Waiting for opponent to join...</p>
        <a href="index.php">Cancel</a>
    </div>
</body>
</html>
