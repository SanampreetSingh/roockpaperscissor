<?php
session_start();

// Validate input
$room = isset($_GET['room']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']) : '';
$player_name = isset($_GET['player_name']) ? htmlspecialchars($_GET['player_name']) : '';

if (empty($room) || empty($player_name)) {
    header("Location: online.php?error=invalid_input");
    exit;
}

// Check if room exists
if (!isset($_SESSION['rooms'][$room]) || count($_SESSION['rooms'][$room]['players']) >= 2) {
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

$opponent = $_SESSION['rooms'][$room]['creator_name'];

header("Location: play_online.php?room=$room&id=$player_id&opponent=".urlencode($opponent));
exit;
?>
