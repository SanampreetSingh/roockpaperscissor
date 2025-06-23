<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']);
    $player_name = htmlspecialchars($_GET['player_name']);
    
    $roomPath = "storage/rooms/$room.json";
    
    if (!file_exists($roomPath)) {
        header("Location: online.php?error=room_not_found");
        exit;
    }
    
    $data = json_decode(file_get_contents($roomPath), true);
    
    if (count($data['players']) >= 2) {
        header("Location: online.php?error=room_full");
        exit;
    }
    
    $playerId = uniqid();
    $data['players'][$playerId] = [
        'name' => $player_name,
        'choice' => null
    ];
    
    file_put_contents($roomPath, json_encode($data));
    header("Location: play_online.php?room=$room&id=$playerId&opponent=".urlencode($data['creator_name']));
    exit;
} else {
    header("Location: online.php");
    exit;
}
?>
