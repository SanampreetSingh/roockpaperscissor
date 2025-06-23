<?php
$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']);
$playerId = $_GET['id'];
$dir = "storage/rooms";

$roomPath = "$dir/$room.json";
$data = json_decode(file_get_contents($roomPath), true);

if (count($data['players']) >= 2) {
    header("Location: online.php?error=room_full");
    exit;
}

if ($data['creator'] !== $playerId) {
    header("Location: play_online.php?room=$room&id=$playerId&opponent=".urlencode($data['creator_name']));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Room</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/online.css">
</head>
<body>
  <div class="container">
    <h2>Room: <?= htmlspecialchars($room) ?></h2>
    <p>Waiting for opponent to join...</p>
    <p>Share this room ID: <strong><?= $room ?></strong></p>
    <div class="btn-group">
      <a href="index.php" class="game-btn">Cancel</a>
    </div>
  </div>
</body>
</html>
