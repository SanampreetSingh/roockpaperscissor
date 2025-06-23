<?php
session_start();
$choices = ['rock', 'paper', 'scissors'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['choice'])) {
    $userChoice = $_POST['choice'];
    $computerChoice = $choices[array_rand($choices)];

    function getResult($user, $computer) {
        if ($user == $computer) return "It's a tie!";
        if (
            ($user == 'rock' && $computer == 'scissors') ||
            ($user == 'scissors' && $computer == 'paper') ||
            ($user == 'paper' && $computer == 'rock')
        ) return "You win!";
        return "Computer wins!";
    }

    $result = getResult($userChoice, $computerChoice);

    // Track score
    if (!isset($_SESSION['cpu_score'])) {
        $_SESSION['cpu_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
    }
    if ($result == "You win!") $_SESSION['cpu_score']['win']++;
    elseif ($result == "Computer wins!") $_SESSION['cpu_score']['lose']++;
    else $_SESSION['cpu_score']['tie']++;
} else {
    header("Location: play_cpu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CPU Result</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/game.css">
</head>
<body>
  <div class="container">
    <h2>Result</h2>
    <p>You chose: <strong><?= htmlspecialchars($userChoice) ?></strong></p>
    <p>Computer chose: <strong><?= htmlspecialchars($computerChoice) ?></strong></p>
    <h3><?= $result ?></h3>

    <div class="scoreboard">
      <h4>Scoreboard</h4>
      <p>Wins: <?= $_SESSION['cpu_score']['win'] ?> |
         Losses: <?= $_SESSION['cpu_score']['lose'] ?> |
         Ties: <?= $_SESSION['cpu_score']['tie'] ?></p>
    </div>

    <div class="btn-group">
      <a href="play_cpu.php" class="game-btn">Play Again</a>
      <a href="reset_cpu.php" class="game-btn reset-btn">Reset Score</a>
      <a href="index.php" class="game-btn">Main Menu</a>
    </div>
  </div>
</body>
</html>
