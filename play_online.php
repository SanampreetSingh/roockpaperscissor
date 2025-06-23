<?php
session_start();
$player = $_GET['id'];
$opponent = $_GET['opponent'];
$room = $_GET['room'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Game</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/game.css">
</head>
<body>
  <div class="container">
    <h1>You're playing against <?= htmlspecialchars($opponent) ?></h1>
    <form action="result_online.php" method="POST">
      <input type="hidden" name="room" value="<?= htmlspecialchars($room) ?>">
      <input type="hidden" name="player" value="<?= htmlspecialchars($player) ?>">
      <input type="hidden" name="opponent" value="<?= htmlspecialchars($opponent) ?>">
      <div class="choice-container">
        <label class="choice">
          <input type="radio" name="choice" value="rock" required>
          <span class="icon rock">✊</span>
          Rock
        </label>
        <label class="choice">
          <input type="radio" name="choice" value="paper">
          <span class="icon paper">✋</span>
          Paper
        </label>
        <label class="choice">
          <input type="radio" name="choice" value="scissors">
          <span class="icon scissors">✌️</span>
          Scissors
        </label>
      </div>
      <button type="submit" class="game-btn">Submit</button>
    </form>
  </div>
</body>
</html>
