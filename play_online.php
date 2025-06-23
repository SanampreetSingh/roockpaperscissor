<?php
session_start();
$player = $_GET['id'];
$opponent = $_GET['opponent'];
$room = $_GET['room'];

// Store game info in session
$_SESSION['current_game'] = [
    'room' => $room,
    'player_id' => $player,
    'opponent' => $opponent
];
?>
<script>

setInterval(() => {
    fetch('connection_check.php?room=<?= $room ?>')
        .then(res => res.json())
        .then(data => {
            if (!data.connected) {
                window.location.href = 'waiting.php?room=<?= $room ?>';
            }
        });
}, 5000);
</script>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Game</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/game.css">
  <style>
    .game-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }
    
    .scoreboard {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
      margin: 20px 0;
      width: 100%;
      max-width: 400px;
    }
    
    .action-buttons {
      margin-top: 30px;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      justify-content: center;
    }
  </style>
</head>
<body>
  <div class="game-container">
    <h1>Playing against <?= htmlspecialchars($opponent) ?></h1>
    
    <div class="scoreboard">
      <h3>Scoreboard</h3>
      <p>Wins: <?= $_SESSION['online_score']['win'] ?? 0 ?></p>
      <p>Losses: <?= $_SESSION['online_score']['lose'] ?? 0 ?></p>
      <p>Ties: <?= $_SESSION['online_score']['tie'] ?? 0 ?></p>
    </div>
    
    <form action="result_online.php" method="POST">
      <input type="hidden" name="room" value="<?= htmlspecialchars($room) ?>">
      <input type="hidden" name="player" value="<?= htmlspecialchars($player) ?>">
      <input type="hidden" name="opponent" value="<?= htmlspecialchars($opponent) ?>">
      
      <div class="choice-container">
        <label class="choice">
          <input type="radio" name="choice" value="rock" required>
          <span class="icon">✊</span>
          Rock
        </label>
        <label class="choice">
          <input type="radio" name="choice" value="paper">
          <span class="icon">✋</span>
          Paper
        </label>
        <label class="choice">
          <input type="radio" name="choice" value="scissors">
          <span class="icon">✌️</span>
          Scissors
        </label>
      </div>
      
      <div class="action-buttons">
        <button type="submit" class="game-btn">Submit Move</button>
        <a href="play_online.php?room=<?= $room ?>&id=<?= $player ?>&opponent=<?= urlencode($opponent) ?>" 
           class="game-btn">Play Again</a>
        <a href="index.php" class="game-btn">Main Menu</a>
      </div>
    </form>
  </div>
</body>
</html>
