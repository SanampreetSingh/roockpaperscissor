<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['room']);
    $player_name = htmlspecialchars($_POST['player_name']);
    
    $dir = "storage/rooms";
    if (!file_exists($dir)) mkdir($dir, 0777, true);
    
    $roomPath = "$dir/$room.json";
    $playerId = uniqid();
    
    $data = [
        'creator' => $playerId,
        'creator_name' => $player_name,
        'players' => [
            $playerId => [
                'name' => $player_name,
                'choice' => null,
                'last_active' => time()
            ]
        ]
    ];
    
    file_put_contents($roomPath, json_encode($data));
    header("Location: room.php?room=$room&id=$playerId&player_name=".urlencode($player_name));
    exit;
} else {
    header("Location: online.php");
    exit;
}
?>
