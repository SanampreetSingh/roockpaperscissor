<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['room']);
    $player_name = htmlspecialchars($_POST['player_name']);
    $player_id = uniqid();
    
    // Initialize session storage if not exists
    if (!isset($_SESSION['rooms'])) {
        $_SESSION['rooms'] = [];
    }
    
    // Create new room
    $_SESSION['rooms'][$room] = [
        'creator' => $player_id,
        'creator_name' => $player_name,
        'players' => [
            $player_id => [
                'name' => $player_name,
                'choice' => null,
                'last_active' => time()
            ]
        ]
    ];
    
    header("Location: room.php?room=$room&id=$player_id");
    exit;
}

header("Location: online.php");
exit;
?>
