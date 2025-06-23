<?php
session_start();
$room = $_GET['room'];
$player = $_GET['player'];
$opponent = $_GET['opponent'];

// Check if opponent rejoins
$path = "storage/rooms/$room.json";
if (file_exists($path)) {
    $data = json_decode(file_get_contents($path), true);
    if (count($data['players']) === 2) {
        header("Location: play_online.php?room=$room&id=$player&opponent=".urlencode($opponent));
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Waiting for Opponent</title>
  <meta http-equiv="refresh" content="5">
  <style>
    .waiting-container {
      text-align: center;
      padding: 20px;
    }
    .spinner {
      border: 5px solid #f3f3f3;
      border-top: 5px solid #6a11cb;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
      margin: 20px auto;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class="waiting-container">
    <h2>Opponent Disconnected</h2>
    <p>Waiting for opponent to rejoin...</p>
    <div class="spinner"></div>
    <p>Room: <?= htmlspecialchars($room) ?></p>
    <a href="index.php" class="game-btn">Leave Game</a>
  </div>
</body>
</html>
