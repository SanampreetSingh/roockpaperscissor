<?php
session_start();

// Validate input
$room = isset($_GET['room']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']) : '';
$player_name = isset($_GET['player_name']) ? htmlspecialchars($_GET['player_name']) : 'Player';

if (empty($room) || empty($player_name)) {
    header("Location: online.php?error=invalid_input");
    exit;
}

// Check if room exists and has space
if (empty($_SESSION['rooms'][$room])) {
    header("Location: online.php?error=room_not_found");
    exit;
}

// Fix: Properly count active players (only count those who haven't timed out)
$active_players = 0;
foreach ($_SESSION['rooms'][$room]['players'] as $player) {
    if (time() - $player['last_active'] < 30) { // 30-second timeout
        $active_players++;
    }
}

if ($active_players >= 2) {
    header("Location: online.php?error=room_full");
    exit;
}

// Add player to room
$player_id = uniqid();
$_SESSION['rooms'][$room]['players'][$player_id] = [
    'name' => $player_name,
    'choice' => null,
    'last_active' => time()
];

// Get opponent name
$opponent = $_SESSION['rooms'][$room]['creator_name'];
header("Location: play_online.php?room=$room&id=$player_id&opponent=".urlencode($opponent));
exit;
?>
