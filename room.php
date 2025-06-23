<?php
session_start();
$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']);
$player = $_GET['id'];

// File paths
$roomFile = "storage/rooms/$room.lock";
$roomData = "storage/rooms/$room.json";

// Create directory if it doesn't exist
if (!file_exists('storage/rooms')) {
    mkdir('storage/rooms', 0777, true);
}

// Atomic file operations with locking
$lock = fopen($roomFile, 'c');
if (!flock($lock, LOCK_EX)) {
    header("Location: online.php?error=room_busy");
    exit;
}

try {
    // Initialize room data structure
    $data = file_exists($roomData) 
            ? json_decode(file_get_contents($roomData), true) 
            : ['players' => [], 'created_at' => time()];

    // Player reconnection/initial join handling
    if (!isset($data['players'][$player])) {
        if (count($data['players']) >= 2) {
            header("Location: online.php?error=room_full");
            exit;
        }
        // Initialize player data with all required fields
        $data['players'][$player] = [
            'choice' => null,
            'last_active' => time(),
            'name' => $_GET['player_name'] ?? 'Player_'.substr($player, 0, 4)
        ];
    } else {
        // Update existing player's activity
        $data['players'][$player]['last_active'] = time();
    }

    // Cleanup inactive players (>30 seconds inactive)
    foreach ($data['players'] as $id => $playerData) {
        if (!isset($playerData['last_active']) || (time() - $playerData['last_active']) > 30) {
            unset($data['players'][$id]);
        }
    }

    // Save updated room state
    file_put_contents($roomData, json_encode($data));

    // Redirect logic
    if (count($data['players']) === 2) {
        $opponent = null;
        foreach ($data['players'] as $id => $p) {
            if ($id !== $player) {
                $opponent = $p['name'] ?? 'Opponent';
                break;
            }
        }
        header("Location: play_online.php?room=$room&id=$player&opponent=".urlencode($opponent));
        exit;
    }

    // Show waiting room
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
            .game-btn {
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
        <h2>Waiting for opponent...</h2>
        <p>Room: <strong><?= htmlspecialchars($room) ?></strong></p>
        <p>Connected players: <?= count($data['players']) ?>/2</p>
        <div class="spinner"></div>
        <p>Page will refresh automatically</p>
        <a href="index.php" class="game-btn">Cancel</a>
    </body>
    </html>
    <?php
} finally {
    flock($lock, LOCK_UN);
    fclose($lock);
}
?>
