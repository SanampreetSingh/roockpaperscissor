<?php
$choices = ['rock', 'paper', 'scissors'];
$userChoice = null;
$computerChoice = null;
$result = null;

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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Play vs Computer</title>
</head>
<body>
  <h1>Rock Paper Scissors - VS Computer</h1>

  <form method="POST">
    <label>Choose:</label><br>
    <input type="radio" name="choice" value="rock" required> Rock<br>
    <input type="radio" name="choice" value="paper"> Paper<br>
    <input type="radio" name="choice" value="scissors"> Scissors<br><br>
    <button type="submit">Play</button>
  </form>

  <?php if ($userChoice !== null): ?>
    <h2>Result</h2>
    <p>You chose: <strong><?= htmlspecialchars($userChoice) ?></strong></p>
    <p>Computer chose: <strong><?= $computerChoice ?></strong></p>
    <h3><?= $result ?></h3>
  <?php endif; ?>
</body>
</html>
