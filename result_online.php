<?php
$player = $_POST['player'];
$opponent = $_POST['opponent'];
$choice = $_POST['choice'];

// Save the current player's choice
file_put_contents("storage/$player-choice.txt", $choice);

// Wait for the opponent's choice
$opponentFile = "storage/$opponent-choice.txt";
$timeout = 10;
$wait = 0;

while (!file_exists($opponentFile) && $wait < $timeout) {
    sleep(1);
    $wait++;
}

if (!file_exists($opponentFile)) {
    echo "<h1>Opponent did not respond in time.</h1>";
    exit;
}

$opponentChoice = trim(file_get_contents($opponentFile));

function getResult($you, $them) {
    if ($you == $them) return "It's a tie!";
    if (
        ($you == 'rock' && $them == 'scissors') ||
        ($you == 'scissors' && $them == 'paper') ||
        ($you == 'paper' && $them == 'rock')
    ) return "You win!";
    return "You lose!";
}

$result = getResult($choice, $opponentChoice);

// 🧹 Cleanup after match
unlink("storage/$player-choice.txt");
unlink("storage/$opponent-choice.txt");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Match Result</title>
</head>
<body>
  <h2>Game Result</h2>
  <p>You chose: <strong><?= htmlspecialchars($choice) ?></strong></p>
  <p>Opponent chose: <strong><?= htmlspecialchars($opponentChoice) ?></strong></p>
  <h3><?= $result ?></h3>
  <a href="index.php">Play Again</a>
</body>
</html>
