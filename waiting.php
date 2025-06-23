<?php
session_start();

// Validate and sanitize input parameters
$room = isset($_GET['room']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']) : '';
$player = isset($_GET['player']) ? $_GET['player'] : '';
$opponent = isset($_GET['opponent']) ? $_GET['opponent'] : 'Opponent';

// Basic validation
if (empty($room) || empty($player)) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

// File paths
$roomFile = "storage/rooms/$room.lock";
$roomData = "storage/rooms/$room.json";

// Check if room exists
if (!file_exists($roomData)) {
    header("Location: online.php?error=room_expired");
    exit;
}

// Get room data with locking
$lock = fopen($roomFile, 'c');
if (!flock($lock, LOCK_EX)) {
    header("Location: online.php?error=room_busy");
    exit;
}

try {
    $data = json_decode(file_get_contents($roomData), true);
    
    // Update player's last active time
    if (isset($data['players'][$player])) {
        $data['players'][$player]['last_active'] = time();
        file_put_contents($roomData, json_encode($data));
    }
    
    // Check if opponent reconnected
    $opponentConnected = false;
    foreach ($data['players'] as $id => $playerData) {
        if ($id !== $player && (time() - $playerData['last_active']) <= 30) {
            $opponentConnected = true;
            $opponent = $playerData['name'] ?? 'Opponent';
            break;
        }
    }
    
    if ($opponentConnected && count($data['players']) === 2) {
        header("Location: play_online.php?room=$room&id=$player&opponent=".urlencode($opponent));
        exit;
    }
} finally {
    flock($lock, LOCK_UN);
    fclose($lock);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Waiting Room</title>
    <meta http-equiv="refresh" content="5">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background: #f5f5f5;
        }
        .waiting-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="waiting-container">
        <h2>Opponent Disconnected</h2>
        <p>Waiting for <?= htmlspecialchars($opponent) ?> to reconnect...</p>
        <p>Room: <strong><?= htmlspecialchars($room) ?></strong></p>
        <div class="spinner"></div>
        <p>This page will refresh automatically every 5 seconds</p>
        <a href="index.php" class="btn">Leave Game</a>
    </div>
</body>
</html>
