<?php
$userChoice = $_POST['choice'];
$choices = ['rock', 'paper', 'scissors'];
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Result</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Result</h1>
  <p>You chose: <strong><?= htmlspecialchars($userChoice) ?></strong></p>
  <p>Computer chose: <strong><?= $computerChoice ?></strong></p>
  <h2><?= $result ?></h2>
  <a href="index.php">Play Again</a>
</body>
</html>
