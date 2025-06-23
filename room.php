<?php
session_start();
$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']);
$player = $_GET['id'];

// Atomic file operations
$roomFile = "storage/rooms/$room.lock";
$roomData = "storage/rooms/$room.json";

// Implement file locking
$lock = fopen($roomFile, 'c');
flock($lock, LOCK_EX);

try {
    if (!file_exists($roomData)) {
        header("Location: online.php?error=room_expired");
        exit;
    }

    $data = json_decode(file_get_contents($roomData), true);

    // Handle player reconnection
    if (!isset($data['players'][$player])) {
        if (count($data['players']) >= 2) {
            header("Location: online.php?error=room_full");
            exit;
        }
        $data['players'][$player] = ['choice' => null, 'last_active' => time()];
    } else {
        $data['players'][$player]['last_active'] = time();
    }

    // Cleanup inactive players (>30 seconds)
    foreach ($data['players'] as $id => $playerData) {
        if (time() - $playerData['last_active'] > 30) {
            unset($data['players'][$id]);
        }
    }

    file_put_contents($roomData, json_encode($data));

    // Redirect to appropriate screen
    if (count($data['players']) === 2) {
        $opponent = null;
        foreach ($data['players'] as $id => $p) {
            if ($id !== $player) $opponent = $p['name'] ?? 'Opponent';
        }
        header("Location: play_online.php?room=$room&id=$player&opponent=".urlencode($opponent));
        exit;
    }

    // Show waiting room
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="refresh" content="3">
        <title>Waiting Room</title>
        <style>
            .spinner { /* Loading animation */ }
        </style>
    </head>
    <body>
        <h2>Waiting for opponent...</h2>
        <p>Connected players: <?= count($data['players']) ?>/2</p>
        <div class="spinner"></div>
        <p>This page refreshes automatically</p>
        <a href="index.php">Cancel</a>
    </body>
    </html>
    <?php
} finally {
    flock($lock, LOCK_UN);
    fclose($lock);
}
?>

