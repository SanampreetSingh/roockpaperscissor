<?php
session_start();

$room = substr(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room'] ?? ''), 0, 20);
$player_name = substr(htmlspecialchars($_GET['player_name'] ?? ''), 0, 20);

if (empty($room) || empty($player_name)) {
    header("Location: online.php?error=invalid_input");
    exit;
}

// Verify room exists and has space
if (!isset($_SESSION['rooms'][$room])) {
    header("Location: online.php?error=room_not_found");
    exit;
}

// Count active players (not timed out)
$active_players = 0;
foreach ($_SESSION['rooms'][$room]['players'] as $player) {
    if (time() - $player['last_active'] < 30) {
        $active_players++;
    }
}

if ($active_players >= 2) {
    header("Location: online.php?error=room_full");
    exit;
}

// Add new player
$player_id = uniqid('player_', true);
$_SESSION['rooms'][$room]['players'][$player_id] = [
    'name' => $player_name,
    'choice' => null,
    'last_active' => time()
];

// Get opponent name
$opponent_name = '';
foreach ($_SESSION['rooms'][$room]['players'] as $id => $player) {
    if ($id !== $player_id) {
        $opponent_name = $player['name'];
        break;
    }
}

header("Location: play_online.php?room=".urlencode($room)."&player_id=".urlencode($player_id)."&opponent=".urlencode($opponent_name));
exit;
?>
