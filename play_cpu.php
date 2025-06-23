<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Play vs Computer</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/game.css">
</head>
<body>
  <div class="container">
    <h2>Choose Your Move</h2>
    <form method="POST" action="result_cpu.php">
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
      <button type="submit" class="game-btn">Play</button>
    </form>
  </div>
</body>
</html>
