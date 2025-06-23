<?php
session_start();

// Validate and sanitize input
$room = isset($_GET['room']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']) : '';
$player_name = isset($_GET['player_name']) ? htmlspecialchars($_GET['player_name']) : '';

if (empty($room) || empty($player_name)) {
    header("Location: online.php?error=invalid_input");
    exit;
}

// File paths
$roomFile = "storage/rooms/$room.lock";
$roomData = "storage/rooms/$room.json";

// Check if room exists
if (!file_exists($roomData)) {
    header("Location: online.php?error=room_not_found");
    exit;
}

// Atomic operations with file locking
$lock = fopen($roomFile, 'c');
if (!flock($lock, LOCK_EX)) {
    header("Location: online.php?error=room_busy");
    exit;
}

try {
    $data = json_decode(file_get_contents($roomData), true);
    
    // Check if room is full
    if (count($data['players']) >= 2) {
        header("Location: online.php?error=room_full");
        exit;
    }
    
    // Generate player ID and add to room
    $playerId = uniqid();
    $data['players'][$playerId] = [
        'name' => $player_name,
        'choice' => null,
        'last_active' => time()
    ];
    
    // Get opponent name (creator)
    $opponent = '';
    foreach ($data['players'] as $id => $player) {
        if ($id !== $playerId) {
            $opponent = $player['name'];
            break;
        }
    }
    
    file_put_contents($roomData, json_encode($data));
    
    // Redirect to game with all required parameters
    header("Location: play_online.php?room=$room&id=$playerId&opponent=".urlencode($opponent));
    exit;
    
} finally {
    flock($lock, LOCK_UN);
    fclose($lock);
}
?>
