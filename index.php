<?php
session_start();

// Session cleanup for abandoned rooms (runs on every page load)
if (isset($_SESSION['rooms'])) {
    foreach ($_SESSION['rooms'] as $room => $data) {
        $inactive = true;
        foreach ($data['players'] as $player) {
            if (time() - ($player['last_active'] ?? 0) < 3600) { // 1 hour timeout
                $inactive = false;
                break;
            }
        }
        if ($inactive) {
            unset($_SESSION['rooms'][$room]);
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rock Paper Scissors Arena</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <div class="container">
    <h1>Rock Paper Scissors Arena</h1>
    <div class="game-options">
      <a href="play_cpu.php" class="game-btn">Battle the Computer</a>
      <a href="online.php" class="game-btn">Online Multiplayer</a>
    </div>
  </div>
</body>
</html>
