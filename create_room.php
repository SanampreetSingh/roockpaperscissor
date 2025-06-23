<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = substr(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['room'] ?? ''), 0, 20);
    $player_name = substr(htmlspecialchars($_POST['player_name'] ?? ''), 0, 20);

    if (empty($room) || empty($player_name)) {
        header("Location: online.php?error=invalid_input");
        exit;
    }

    // Initialize rooms array if needed
    if (!isset($_SESSION['rooms'])) {
        $_SESSION['rooms'] = [];
    }

    // Create room with timestamp and player
    $player_id = uniqid('player_', true);
    $_SESSION['rooms'][$room] = [
        'created_at' => time(),
        'creator_id' => $player_id,
        'players' => [
            $player_id => [
                'name' => $player_name,
                'choice' => null,
                'last_active' => time()
            ]
        ]
    ];

    header("Location: room.php?room=".urlencode($room)."&player_id=".urlencode($player_id));
    exit;
}

header("Location: online.php");
exit;
?>
