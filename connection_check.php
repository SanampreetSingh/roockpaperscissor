<?php
$room = $_GET['room'];
$roomData = "storage/rooms/$room.json";
header('Content-Type: application/json');

if (!file_exists($roomData)) {
    echo json_encode(['connected' => false]);
    exit;
}

$data = json_decode(file_get_contents($roomData), true);
$playersActive = 0;

foreach ($data['players'] as $p) {
    if (time() - $p['last_active'] < 10) {
        $playersActive++;
    }
}

echo json_encode(['connected' => $playersActive > 1]);
?>
